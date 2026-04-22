@extends('layouts.app')

@section('title', 'Login - Sistem Kas Kelas')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Login</h1>
            <p>Masuk ke akun Anda</p>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="form-input">
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required class="form-input">
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-large">
                <span class="btn-text">Sign In</span>
            </button>
        </form>

        <div class="auth-footer">
            <p>Don't have an account? <a href="{{ route('register') }}" class="link">Register here</a></p>
        </div>
    </div>
</div>
@endsection
