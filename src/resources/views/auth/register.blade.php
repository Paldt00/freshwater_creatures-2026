@extends('layouts.public')

@section('title', 'Register')

@section('content')
<div class="container">
    <div class="auth-wrap">
        <div class="form-card">
            <h2>Register</h2>
            <p style="color: var(--muted); margin-top: -8px;">
                Buat akun untuk ikut berkontribusi dalam pembaruan data ikan air tawar.
            </p>

            @if($errors->any())
                <div class="alert danger">
                    Registrasi belum berhasil. Silakan periksa data yang diisi.
                </div>
            @endif

            <form action="{{ route('public.register') }}" method="POST">
                @csrf

                <div class="field">
                    <label for="name">Nama</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field" style="margin-top: 16px;">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required>
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

                <div class="field" style="margin-top: 16px;">
                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required>
                </div>

                <div style="margin-top: 22px; display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="submit" class="btn">Register</button>
                    <a href="{{ route('public.login.form') }}" class="btn secondary">Sudah Punya Akun</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
