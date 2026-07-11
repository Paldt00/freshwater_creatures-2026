@extends('layouts.public')

@section('title', 'Masuk')

@push('styles')
    <style>
        .auth-shell {
            position: relative;
            min-height: calc(100vh - 120px);
            display: grid;
            place-items: center;
            padding: 54px 16px;
            overflow: hidden;
        }

        .auth-background {
            position: fixed;
            inset: 0;
            z-index: -2;
            overflow: hidden;
            background:
                linear-gradient(
                    135deg,
                    rgba(8, 32, 50, .92),
                    rgba(15, 76, 117, .82)
                );
        }

        .auth-background iframe {
            width: 100%;
            height: 100%;
            border: 0;
            transform: scale(1.04);
            filter: blur(6px);
            opacity: .28;
            pointer-events: none;
        }

        .auth-background::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(
                    circle at top left,
                    rgba(0, 180, 216, .32),
                    transparent 34%
                ),
                linear-gradient(
                    135deg,
                    rgba(8, 32, 50, .72),
                    rgba(15, 76, 117, .64)
                );
        }

        .auth-card {
            width: min(100%, 500px);
            position: relative;
            padding: 34px;
            border-radius: 28px;
            background: rgba(255, 255, 255, .92);
            border: 1px solid rgba(255, 255, 255, .65);
            box-shadow:
                0 28px 70px rgba(8, 32, 50, .24);
            backdrop-filter: blur(18px);
        }

        .auth-close-button {
            position: absolute;
            top: 18px;
            right: 18px;
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: #ffffff;
            color: var(--navy);
            font-size: 22px;
            font-weight: 900;
            line-height: 1;
            box-shadow: var(--shadow-sm);
            transition: .2s ease;
        }

        .auth-close-button:hover {
            background: var(--cyan-soft);
            color: var(--blue);
            transform: translateY(-1px);
        }

        .auth-card h1 {
            margin: 0 42px 10px 0;
            color: var(--navy);
            font-size: 31px;
            line-height: 1.2;
            letter-spacing: -.3px;
        }

        .auth-card p {
            margin: 0 0 24px;
            color: var(--muted);
        }

        .auth-form {
            display: grid;
            gap: 18px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
            font-weight: 800;
        }

        .remember-row input {
            width: 18px;
            height: 18px;
            flex: 0 0 auto;
        }

        .auth-actions {
            display: grid;
            gap: 12px;
            margin-top: 4px;
        }

        .auth-bottom-text {
            margin-top: 18px;
            text-align: center;
            color: var(--muted);
        }

        .auth-bottom-text a {
            color: var(--blue);
            font-weight: 900;
        }

        .auth-bottom-text a:hover {
            color: var(--cyan);
        }

        @media (max-width: 560px) {
            .auth-shell {
                padding: 34px 14px;
            }

            .auth-card {
                padding: 28px 22px;
                border-radius: 22px;
            }

            .auth-card h1 {
                font-size: 27px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="auth-shell">
        <div
            class="auth-background"
            aria-hidden="true"
        >
            <iframe
                src="{{ route('home') }}"
                tabindex="-1"
                loading="lazy"
            ></iframe>
        </div>

        <div class="auth-card">
            <a
                href="{{ route('home') }}"
                class="auth-close-button"
                aria-label="Kembali ke beranda"
            >
                ×
            </a>

            <h1>
                Masuk
            </h1>

            <p>
                Masuk menggunakan akun yang sudah terdaftar untuk mengakses fitur pengajuan data.
            </p>

            <form
                action="{{ route('public.login') }}"
                method="POST"
                class="auth-form"
            >
                @csrf

                <div class="field">
                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email"
                        autocomplete="email"
                        required
                        autofocus
                    >

                    @error('email')
                        <span class="error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">
                        Kata Sandi
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan kata sandi"
                        autocomplete="current-password"
                        required
                    >

                    @error('password')
                        <span class="error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <label class="remember-row">
                    <input
                        type="checkbox"
                        name="remember"
                        value="1"
                        {{ old('remember') ? 'checked' : '' }}
                    >

                    <span>
                        Ingat saya
                    </span>
                </label>

                <div class="auth-actions">
                    <button
                        type="submit"
                        class="btn"
                    >
                        Masuk
                    </button>
                </div>
            </form>

            <div class="auth-bottom-text">
                Belum punya akun?

                <a href="{{ route('public.register.form') }}">
                    Daftar sekarang
                </a>
            </div>
        </div>
    </div>
@endsection
