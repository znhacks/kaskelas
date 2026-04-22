<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas; // ✅ BENAR
use App\Models\Activity;

class KasController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'jenis' => ['required', 'string'],
            'jumlah' => ['required', 'numeric'],
        ]);

        $kas = Kas::create([
            'tanggal' => $request->tanggal,
            'jenis' => $request->jenis,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'dibuat_oleh' => auth()->id(),
        ]);

        // Log activity
        Activity::create([
            'user_id' => auth()->id(),
            'kas_id' => $kas->id,
            'action' => 'create',
            'new_data' => $validated,
            'description' => 'Menambah transaksi ' . ($request->jenis === 'masuk' ? 'pemasukan' : 'pengeluaran') . ' sebesar Rp ' . number_format($request->jumlah, 0, ',', '.')
        ]);

        return redirect()->route('admin.data-kas')
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    /**
     * Store a kas payment from a regular user.
     */
    public function pay(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $kas = Kas::create([
            'tanggal' => $validated['tanggal'],
            'jenis' => 'masuk',
            'jumlah' => $validated['jumlah'],
            'keterangan' => $validated['keterangan'] ?? null,
            'dibuat_oleh' => auth()->id(),
        ]);

        Activity::create([
            'user_id' => auth()->id(),
            'kas_id' => $kas->id,
            'action' => 'pay',
            'new_data' => $validated,
            'description' => 'Bayar kas sebesar Rp ' . number_format($validated['jumlah'], 0, ',', '.') . '.',
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Pembayaran kas berhasil dicatat.');
    }

    /**
     * Update the specified kas transaction.
     */
    public function update(Request $request, Kas $kas)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'jenis' => ['required', 'string'],
            'jumlah' => ['required', 'numeric'],
        ]);

        $oldData = $kas->only(['tanggal', 'jenis', 'jumlah', 'keterangan']);

        $kas->update([
            'tanggal' => $request->tanggal,
            'jenis' => $request->jenis,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
        ]);

        // Log activity
        Activity::create([
            'user_id' => auth()->id(),
            'kas_id' => $kas->id,
            'action' => 'update',
            'old_data' => $oldData,
            'new_data' => $validated,
            'description' => 'Mengubah transaksi dari ' . ($oldData['jenis'] === 'masuk' ? 'pemasukan' : 'pengeluaran') . ' Rp ' . number_format($oldData['jumlah'], 0, ',', '.') . ' menjadi ' . ($request->jenis === 'masuk' ? 'pemasukan' : 'pengeluaran') . ' Rp ' . number_format($request->jumlah, 0, ',', '.')
        ]);

        return redirect()->route('admin.data-kas')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Remove the specified kas transaction.
     */
    public function destroy(Kas $kas)
    {
        $oldData = $kas->only(['tanggal', 'jenis', 'jumlah', 'keterangan']);

        // Log activity before deletion
        Activity::create([
            'user_id' => auth()->id(),
            'kas_id' => $kas->id,
            'action' => 'delete',
            'old_data' => $oldData,
            'description' => 'Menghapus transaksi ' . ($oldData['jenis'] === 'masuk' ? 'pemasukan' : 'pengeluaran') . ' sebesar Rp ' . number_format($oldData['jumlah'], 0, ',', '.')
        ]);

        $kas->delete();

        return redirect()->route('admin.data-kas')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}