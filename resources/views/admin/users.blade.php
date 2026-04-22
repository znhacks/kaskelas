@extends('layouts.app')

@section('title', 'Manajemen Pengguna - Sistem Kas Kelas')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-content">
            <h1>Manajemen Pengguna</h1>
            <p>Promosikan atau turunkan peran pengguna yang terdaftar.</p>
        </div>
    </div>

    <div class="admin-section">
        <h2>Perbarui Peran Pengguna</h2>
        <form method="POST" action="{{ route('admin.users.role.update') }}" class="form-grid">
            @csrf
            <div class="form-group">
                <label for="email">Email Pengguna</label>
                <input id="email" name="email" type="email" class="form-input" value="{{ old('email') }}" placeholder="Masukkan email pengguna" required>
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label for="role">Pilih Peran</label>
                <select id="role" name="role" class="form-input" required>
                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-actions full-width">
                <button type="submit" class="btn btn-primary">Perbarui Peran</button>
            </div>
        </form>
    </div>

    <div class="admin-section">
        <h2>Daftar Pengguna</h2>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Peran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td class="action-buttons">
                                @if($user->role === 'admin')
                                    <form method="POST" action="{{ route('admin.users.role.update') }}">
                                        @csrf
                                        <input type="hidden" name="email" value="{{ $user->email }}">
                                        <input type="hidden" name="role" value="user">
                                        <button type="submit" class="btn btn-secondary btn-sm">Turunkan ke User</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.users.role.update') }}">
                                        @csrf
                                        <input type="hidden" name="email" value="{{ $user->email }}">
                                        <input type="hidden" name="role" value="admin">
                                        <button type="submit" class="btn btn-primary btn-sm">Promosikan ke Admin</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada pengguna terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
