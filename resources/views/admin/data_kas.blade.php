@extends('layouts.app')

@section('title', 'Data Kas - Sistem Kas Kelas')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-content">
            <h1>Data Kas</h1>
            <p>Kelola semua transaksi kas kelas</p>
        </div>
        <a href="{{ route('admin.data-kas.create') }}" class="btn btn-primary">+ Tambah Transaksi</a>
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-icon">💰</div>
            <h3>Saldo Saat Ini</h3>
            <p class="card-value">Rp {{ number_format($transactions->where('jenis', 'masuk')->sum('jumlah') - $transactions->where('jenis', 'keluar')->sum('jumlah'), 0, ',', '.') }}</p>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">📥</div>
            <h3>Total Pemasukan</h3>
            <p class="card-value">Rp {{ number_format($transactions->where('jenis', 'masuk')->sum('jumlah'), 0, ',', '.') }}</p>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">📤</div>
            <h3>Total Pengeluaran</h3>
            <p class="card-value">Rp {{ number_format($transactions->where('jenis', 'keluar')->sum('jumlah'), 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="admin-section">
        <h2>Daftar Transaksi</h2>

        <!-- Search Form -->
        <div class="search-container" style="margin-bottom: 1.5rem;">
            <form method="GET" action="{{ route('admin.data-kas') }}" class="search-form">
                <div class="search-input-group">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari berdasarkan deskripsi, tipe, atau nama pencatat..." class="search-input">
                    <button type="submit" class="btn btn-primary search-btn">🔍 Cari</button>
                    @if(request('search'))
                        <a href="{{ route('admin.data-kas') }}" class="btn btn-secondary clear-btn">❌ Clear</a>
                    @endif
                </div>
                @if(request('sort_by'))
                    <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                    <input type="hidden" name="sort_direction" value="{{ request('sort_direction') }}">
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>
                            <a href="{{ route('admin.data-kas', array_merge(request()->query(), ['sort_by' => 'tanggal', 'sort_direction' => (request('sort_by') === 'tanggal' && request('sort_direction') === 'desc') ? 'asc' : 'desc'])) }}" class="sort-link">
                                Tanggal
                                @if(request('sort_by') === 'tanggal')
                                    {{ request('sort_direction') === 'desc' ? '↓' : '↑' }}
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('admin.data-kas', array_merge(request()->query(), ['sort_by' => 'jenis', 'sort_direction' => (request('sort_by') === 'jenis' && request('sort_direction') === 'desc') ? 'asc' : 'desc'])) }}" class="sort-link">
                                Tipe
                                @if(request('sort_by') === 'jenis')
                                    {{ request('sort_direction') === 'desc' ? '↓' : '↑' }}
                                @endif
                            </a>
                        </th>
                        <th>Deskripsi</th>
                        <th>
                            <a href="{{ route('admin.data-kas', array_merge(request()->query(), ['sort_by' => 'jumlah', 'sort_direction' => (request('sort_by') === 'jumlah' && request('sort_direction') === 'desc') ? 'asc' : 'desc'])) }}" class="sort-link">
                                Jumlah
                                @if(request('sort_by') === 'jumlah')
                                    {{ request('sort_direction') === 'desc' ? '↓' : '↑' }}
                                @endif
                            </a>
                        </th>
                        <th>Dicatat Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($transaction->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $transaction->jenis === 'masuk' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                            <td>{{ $transaction->keterangan ?: '-' }}</td>
                            <td>Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}</td>
                            <td>{{ $transaction->user ? $transaction->user->name : 'Unknown' }}</td>
                            <td>
                                <a href="{{ route('admin.data-kas.edit', $transaction) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('admin.data-kas.destroy', $transaction) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-error btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
