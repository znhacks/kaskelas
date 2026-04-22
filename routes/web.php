<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KasController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Welcome page (redirect to dashboard if authenticated)
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// ========================================
// AUTHENTICATION ROUTES (Public)
// ========================================
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ========================================
// LOGOUT (Authenticated Users Only)
// ========================================
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ========================================
// USER ROUTES (Authenticated Users Only)
// ========================================
Route::middleware(['auth'])->group(function () {
    // User Dashboard
    Route::get('/dashboard', function () {
        $totalPemasukan = \App\Models\Kas::where('jenis', 'masuk')->sum('jumlah');
        $totalPengeluaran = \App\Models\Kas::where('jenis', 'keluar')->sum('jumlah');
        $currentSaldo = $totalPemasukan - $totalPengeluaran;
        $recentTransactions = \App\Models\Kas::orderBy('tanggal', 'desc')->take(5)->get();

        return view('dashboard', compact('currentSaldo', 'recentTransactions'));
    })->name('dashboard');

    // User History (Riwayat Kas) - Read-only
    Route::get('/riwayat', function () {
        $transactions = \App\Models\Kas::where('dibuat_oleh', auth()->id())
            ->orderBy('tanggal', 'desc')
            ->get();
        $totalPemasukan = \App\Models\Kas::where('jenis', 'masuk')
            ->where('dibuat_oleh', auth()->id())
            ->sum('jumlah');
        $totalPengeluaran = \App\Models\Kas::where('jenis', 'keluar')
            ->where('dibuat_oleh', auth()->id())
            ->sum('jumlah');
        $currentSaldo = $totalPemasukan - $totalPengeluaran;

        return view('user.riwayat', compact('transactions', 'currentSaldo', 'totalPemasukan', 'totalPengeluaran'));
    })->name('riwayat');

    Route::post('/bayar-kas', [KasController::class, 'pay'])->name('kas.pay');
});

// ========================================
// ADMIN ROUTES (Admin Users Only)
// ========================================
Route::middleware(['auth', 'admin'])->prefix('/admin')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', function () {
        $totalPemasukan = \App\Models\Kas::where('jenis', 'masuk')->sum('jumlah');
        $totalPengeluaran = \App\Models\Kas::where('jenis', 'keluar')->sum('jumlah');
        $totalSaldo = $totalPemasukan - $totalPengeluaran;

        return view('admin.dashboard', compact('totalSaldo', 'totalPemasukan', 'totalPengeluaran'));
    })->name('admin.dashboard');

    // Kas Data Management - Full CRUD
    Route::get('/data-kas', function () {
        $sortBy = request('sort_by', 'tanggal');
        $sortDirection = request('sort_direction', 'desc');
        $search = request('search');

        $query = \App\Models\Kas::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('keterangan', 'like', '%' . $search . '%')
                  ->orWhere('jenis', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $transactions = $query->orderBy($sortBy, $sortDirection)->get();
        return view('admin.data_kas', compact('transactions', 'sortBy', 'sortDirection', 'search'));
    })->name('admin.data-kas');

    // Create Transaction Form
    Route::get('/data-kas/create', function () {
        return view('admin.data_kas_create');
    })->name('admin.data-kas.create');

    // Store Transaction
    Route::post('/data-kas', [KasController::class, 'store'])->name('admin.data-kas.store');

    // Edit Transaction Form
    Route::get('/data-kas/{kas}/edit', function (\App\Models\Kas $kas) {
        return view('admin.data_kas_edit', compact('kas'));
    })->name('admin.data-kas.edit');

    // Update Transaction
    Route::put('/data-kas/{kas}', [KasController::class, 'update'])->name('admin.data-kas.update');

    // Delete Transaction
    Route::delete('/data-kas/{kas}', [KasController::class, 'destroy'])->name('admin.data-kas.destroy');

    // Activity History - Read-only
    Route::get('/history', function () {
        $search = request('search');

        $query = \App\Models\Activity::with('user:id,name');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', '%' . $search . '%')
                  ->orWhere('action', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $activities = $query->orderBy('created_at', 'desc')->get();
        return view('admin.history', compact('activities', 'search'));
    })->name('admin.history');

    // Admin User Role Management
    Route::get('/users', function () {
        $users = \App\Models\User::orderBy('name')->get();
        return view('admin.users', compact('users'));
    })->name('admin.users');

    Route::post('/users/role', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'role' => ['required', 'in:admin,user'],
        ]);

        $user = \App\Models\User::where('email', $validated['email'])->first();
        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        if ($user->role === $validated['role']) {
            return back()->with('error', 'User sudah memiliki peran yang dipilih.');
        }

        $user->role = $validated['role'];
        $user->save();

        return back()->with('success', 'Peran untuk ' . $user->email . ' berhasil diperbarui menjadi ' . ucfirst($validated['role']) . '.');
    })->name('admin.users.role.update');
});
