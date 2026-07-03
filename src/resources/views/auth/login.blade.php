@extends('layouts.public')

@section('title', 'Login')

@section('content')
<div class="container">
    <div class="auth-wrap">
        <div class="form-card">
            <h2>Login</h2>
            <p style="color: var(--muted); margin-top: -8px;">
                Masuk untuk mengajukan penambahan atau perubahan data ikan.
            </p>

            @if($errors->any())
                <div class="alert danger">
                    Data login belum sesuai. Silakan periksa kembali.
                </div>
            @endif

            <form action="{{ route('public.login') }}" method="POST">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field" style="margin-top: 16px;">
                    <label for="password">Kata Sandi</label>
                    <input type="password" name="password" id="password" required>
                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-top: 16px;">
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="remember" value="1" style="width: auto;">
                        Ingat saya
                    </label>
                </div>

                <div style="margin-top: 22px; display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="submit" class="btn">Login</button>
                    <a href="{{ route('public.register.form') }}" class="btn secondary">Buat Akun</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
