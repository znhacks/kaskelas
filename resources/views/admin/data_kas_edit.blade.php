@extends('layouts.app')

@section('title', 'Edit Transaksi - Sistem Kas Kelas')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-content">
            <h1>Edit Transaksi</h1>
            <p>Perbarui data transaksi kas</p>
        </div>
    </div>

    <div class="admin-section">
        <form method="POST" action="{{ route('admin.data-kas.update', $kas) }}" class="form-grid">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input id="tanggal" name="tanggal" type="date" class="form-input" value="{{ \Carbon\Carbon::parse($kas->tanggal)->format('Y-m-d') }}" />
            </div>

            <div class="form-group">
                <label for="jenis">Jenis</label>
                <select id="jenis" name="jenis" class="form-select">
                    <option value="masuk" {{ $kas->jenis === 'masuk' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="keluar" {{ $kas->jenis === 'keluar' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>

            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input id="jumlah" name="jumlah" type="number" class="form-input" placeholder="Masukkan jumlah" value="{{ $kas->jumlah }}" />
            </div>

            <div class="form-group">
                <label for="keterangan">Deskripsi</label>
                <textarea id="keterangan" name="keterangan" class="form-textarea" rows="4" placeholder="Deskripsi singkat (opsional)">{{ $kas->keterangan }}</textarea>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.data-kas') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Perbarui Transaksi</button>
            </div>
        </form>
    </div>
</div>
@endsection