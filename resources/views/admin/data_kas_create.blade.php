@extends('layouts.app')

@section('title', 'Tambah Transaksi - Sistem Kas Kelas')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-content">
            <h1>Tambah Transaksi</h1>
            <p>Catat transaksi kas baru</p>
        </div>
    </div>

    <div class="admin-section">
        <form method="POST" action="{{ route('admin.data-kas.store') }}" class="form-grid">
            @csrf

            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input id="tanggal" name="tanggal" type="date" class="form-input" />
            </div>

            <div class="form-group">
                <label for="jenis">Jenis</label>
                <select id="jenis" name="jenis" class="form-select">
                    <option value="masuk">Pemasukan</option>
                    <option value="keluar">Pengeluaran</option>
                </select>
            </div>

            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input id="jumlah" name="jumlah" type="number" class="form-input" placeholder="Masukkan jumlah" />
            </div>

            <div class="form-group">
                <label for="keterangan">Deskripsi</label>
                <textarea id="keterangan" name="keterangan" class="form-textarea" rows="4" placeholder="Deskripsi singkat (opsional)"></textarea>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.data-kas') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
            </div>
        </form>
    </div>
</div>
@endsection
