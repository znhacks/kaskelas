@extends('layouts.app')

@section('title', 'Dashboard - Sistem Kas Kelas')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-content">
            <h1>Dashboard</h1>
            <p>Ringkasan kas kelas Anda</p>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-icon">💰</div>
            <h3>Saldo Saat Ini</h3>
            <p class="card-value">Rp {{ number_format($currentSaldo, 0, ',', '.') }}</p>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">📥</div>
            <h3>Total Pemasukan</h3>
            <p class="card-value">Rp {{ number_format($recentTransactions->where('jenis', 'masuk')->sum('jumlah'), 0, ',', '.') }}</p>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">📤</div>
            <h3>Total Pengeluaran</h3>
            <p class="card-value">Rp {{ number_format($recentTransactions->where('jenis', 'keluar')->sum('jumlah'), 0, ',', '.') }}</p>
        </div>
    </div>

    @user
    <div class="admin-section">
        <h2>Bayar Kas</h2>
        <form method="POST" action="{{ route('kas.pay') }}" class="form-grid">
            @csrf
            <div class="form-group">
                <label for="tanggal">Tanggal Pembayaran</label>
                <input id="tanggal" name="tanggal" type="date" class="form-input" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required>
                @error('tanggal')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label for="jumlah">Jumlah (Rp)</label>
                <input id="jumlah" name="jumlah" type="number" class="form-input" min="1" value="{{ old('jumlah') }}" placeholder="Masukkan jumlah pembayaran" required>
                @error('jumlah')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group full-width">
                <label for="keterangan">Keterangan</label>
                <textarea id="keterangan" name="keterangan" class="form-textarea" rows="3" placeholder="Contoh: Pembayaran kas bulan ini">{{ old('keterangan') }}</textarea>
                @error('keterangan')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-actions full-width">
                <button type="submit" class="btn btn-primary">Bayar Kas</button>
            </div>
        </form>
    </div>
    @enduser

    @admin
    <div class="admin-section">
        <h2>Transaksi Terbaru</h2>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Deskripsi</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $transaction)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($transaction->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $transaction->jenis === 'masuk' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                            <td>{{ $transaction->keterangan ?: '-' }}</td>
                            <td>Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endadmin
</div>
@endsection
