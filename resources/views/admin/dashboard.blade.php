@extends('layouts.app')

@section('title', 'Admin Dashboard - Sistem Kas Kelas')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-content">
            <h1>Admin Dashboard</h1>
            <p>Ringkasan sistem kas kelas</p>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-icon">💰</div>
            <h3>Total Saldo</h3>
            <p class="card-value">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</p>
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
</div>
@endsection
