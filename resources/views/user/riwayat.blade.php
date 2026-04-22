@extends('layouts.app')

@section('title', 'Riwayat Kas - Sistem Kas Kelas')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-content">
            <h1>Riwayat Pembayaran Anda</h1>
            <p>Daftar transaksi kas yang Anda lakukan sendiri</p>
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
            <p class="card-value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">📤</div>
            <h3>Total Pengeluaran</h3>
            <p class="card-value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="admin-section">
        <h2>Daftar Transaksi</h2>
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
                    @forelse($transactions as $transaction)
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
</div>
@endsection
