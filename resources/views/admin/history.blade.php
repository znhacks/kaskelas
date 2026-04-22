@extends('layouts.app')

@section('title', 'Riwayat Aktivitas - Sistem Kas Kelas')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-content">
            <h1>Riwayat Aktivitas</h1>
            <p>Log semua aktivitas sistem kas kelas</p>
        </div>
        <button class="btn btn-secondary">📥 Filter</button>
    </div>

    <div class="admin-section">
        <h2>Riwayat Aktivitas</h2>

        <!-- Search Form -->
        <div class="search-container" style="margin-bottom: 1.5rem;">
            <form method="GET" action="{{ route('admin.history') }}" class="search-form">
                <div class="search-input-group">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari berdasarkan aktivitas, pengguna, atau keterangan..." class="search-input">
                    <button type="submit" class="btn btn-primary search-btn">🔍 Cari</button>
                    @if(request('search'))
                        <a href="{{ route('admin.history') }}" class="btn btn-secondary clear-btn">❌ Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pengguna</th>
                        <th>Aktivitas</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                        <tr>
                            <td>{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $activity->user ? $activity->user->name : 'Unknown' }}</td>
                            <td>
                                @if($activity->action === 'create')
                                    Tambah Transaksi
                                @elseif($activity->action === 'update')
                                    Ubah Transaksi
                                @elseif($activity->action === 'delete')
                                    Hapus Transaksi
                                @else
                                    {{ ucfirst($activity->action) }}
                                @endif
                            </td>
                            <td>
                                @php
                                    $amount = null;
                                    if ($activity->action === 'delete') {
                                        $amount = $activity->old_data['jumlah'] ?? null;
                                    } elseif ($activity->new_data['jumlah'] ?? false) {
                                        $amount = $activity->new_data['jumlah'];
                                    } else {
                                        $amount = $activity->old_data['jumlah'] ?? 0;
                                    }
                                @endphp
                                Rp {{ number_format($amount ?: 0, 0, ',', '.') }}
                            </td>
                            <td>{{ $activity->description ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada aktivitas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
