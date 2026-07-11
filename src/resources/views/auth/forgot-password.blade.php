@extends('layouts.public')

@section('title', 'Lupa Kata Sandi')

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
            width: min(100%, 520px);
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
            font-size: 30px;
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

        .auth-actions {
            display: grid;
            gap: 12px;
            margin-top: 4px;
        }

        .auth-link {
            color: var(--blue);
            font-weight: 900;
            text-align: center;
        }

        .auth-link:hover {
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
                font-size: 26px;
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
                Lupa Kata Sandi
            </h1>

            <p>
                Masukkan email akun Anda. Sistem akan mengirimkan tautan untuk membuat kata sandi baru.
            </p>

            <form
                action="{{ route('password.email') }}"
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
                        placeholder="Masukkan email akun Anda"
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

                <div class="auth-actions">
                    <button
                        type="submit"
                        class="btn"
                    >
                        Kirim Link Reset
                    </button>

                    <a
                        href="{{ route('public.login.form') }}"
                        class="auth-link"
                    >
                        Kembali ke halaman masuk
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
