@extends('layouts.public')

@section('title', 'Masuk')

@push('styles')
<style>
    .auth-scene {
        position: relative;
        isolation: isolate;
        min-height: calc(100vh - 86px);
        padding: 64px 20px;
        display: grid;
        place-items: center;
        overflow: hidden;
    }

    .auth-home-preview {
        position: absolute;
        inset: -20px;
        z-index: -3;
        width: calc(100% + 40px);
        height: calc(100% + 40px);
        border: 0;
        pointer-events: none;
        filter: blur(10px);
        transform: scale(1.04);
        opacity: .85;
    }

    .auth-scene-overlay {
        position: absolute;
        inset: 0;
        z-index: -2;
        background:
            linear-gradient(
                135deg,
                rgba(230, 250, 255, .68),
                rgba(244, 251, 255, .84)
            );
        backdrop-filter: blur(3px);
    }

    .auth-scene-decoration {
        position: absolute;
        z-index: -1;
        width: 380px;
        height: 380px;
        border-radius: 50%;
        background: rgba(0, 180, 216, .16);
        filter: blur(20px);
    }

    .auth-scene-decoration.one {
        top: -160px;
        left: -120px;
    }

    .auth-scene-decoration.two {
        right: -140px;
        bottom: -180px;
        background: rgba(15, 76, 117, .14);
    }

    .auth-panel {
        width: min(100%, 560px);
        padding: 34px;
        border: 1px solid rgba(255, 255, 255, .78);
        border-radius: 28px;
        background: rgba(255, 255, 255, .88);
        box-shadow:
            0 28px 70px rgba(8, 32, 50, .20);
        backdrop-filter: blur(24px);
    }

    .auth-panel-header {
        margin-bottom: 24px;
    }

    .auth-panel-header h1 {
        margin: 0 0 8px;
        color: var(--navy);
        font-size: 32px;
        line-height: 1.2;
    }

    .auth-panel-header p {
        margin: 0;
        color: var(--muted);
    }

    .auth-icon {
        width: 58px;
        height: 58px;
        margin-bottom: 18px;
        border-radius: 20px;
        display: grid;
        place-items: center;
        color: white;
        font-size: 27px;
        background:
            linear-gradient(
                135deg,
                var(--blue),
                var(--cyan)
            );
        box-shadow:
            0 14px 30px rgba(15, 76, 117, .26);
    }

    .auth-form {
        display: grid;
        gap: 18px;
    }

    .auth-password-wrapper {
        position: relative;
    }

    .auth-password-wrapper input {
        padding-right: 52px;
    }

    .password-toggle {
        position: absolute;
        top: 50%;
        right: 10px;
        width: 38px;
        height: 38px;
        padding: 0;
        border: 0;
        border-radius: 11px;
        background: transparent;
        color: var(--muted);
        cursor: pointer;
        transform: translateY(-50%);
        box-shadow: none;
    }

    .password-toggle:hover {
        color: var(--blue);
        background: var(--cyan-soft);
        transform: translateY(-50%);
        box-shadow: none;
    }

    .remember-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .remember-row input {
        width: 18px;
        height: 18px;
        margin: 0;
        accent-color: var(--blue);
    }

    .remember-row label {
        cursor: pointer;
    }

    .auth-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 4px;
    }

    .auth-actions .btn {
        min-width: 110px;
    }

    .auth-help {
        margin-top: 22px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
        color: var(--muted);
        text-align: center;
    }

    .auth-help a {
        color: var(--blue);
        font-weight: 900;
    }

    @media (max-width: 640px) {
        .auth-scene {
            padding: 36px 14px;
        }

        .auth-panel {
            padding: 24px 20px;
            border-radius: 22px;
        }

        .auth-panel-header h1 {
            font-size: 27px;
        }

        .auth-actions {
            display: grid;
            grid-template-columns: 1fr;
        }

        .auth-actions .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<section class="auth-scene">
    <iframe
        src="{{ route('home') }}"
        class="auth-home-preview"
        tabindex="-1"
        aria-hidden="true"
        title="Latar belakang halaman utama"
    ></iframe>

    <div class="auth-scene-overlay"></div>
    <div class="auth-scene-decoration one"></div>
    <div class="auth-scene-decoration two"></div>

    <div class="auth-panel">
        <div class="auth-icon">
            🔐
        </div>

        <div class="auth-panel-header">
            <h1>Masuk</h1>

            <p>
                Masuk untuk mengajukan penambahan atau perubahan
                data ikan air tawar.
            </p>
        </div>

        @if($errors->any())
            <div class="alert danger">
                {{ $errors->first() }}
            </div>
        @endif

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
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    placeholder="nama@email.com"
                    autocomplete="email"
                    autofocus
                    required
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

                <div class="auth-password-wrapper">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        autocomplete="current-password"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        data-password-toggle="password"
                        aria-label="Tampilkan kata sandi"
                    >
                        👁
                    </button>
                </div>

                @error('password')
                    <span class="error">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="remember-row">
                <input
                    type="checkbox"
                    name="remember"
                    id="remember"
                    value="1"
                    @checked(old('remember'))
                >

                <label for="remember">
                    Ingat saya
                </label>
            </div>

            <div class="auth-actions">
                <button
                    type="submit"
                    class="btn"
                >
                    Masuk
                </button>

                <a
                    href="{{ route('public.register.form') }}"
                    class="btn secondary"
                >
                    Buat Akun
                </a>
            </div>
        </form>

        <div class="auth-help">
            Belum mempunyai akun?

            <a href="{{ route('public.register.form') }}">
                Daftar sekarang
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {
            document
                .querySelectorAll(
                    '[data-password-toggle]'
                )
                .forEach(function (button) {
                    button.addEventListener(
                        'click',
                        function () {
                            const inputId =
                                button.dataset.passwordToggle;

                            const input =
                                document.getElementById(inputId);

                            if (!input) {
                                return;
                            }

                            const isPassword =
                                input.type === 'password';

                            input.type = isPassword
                                ? 'text'
                                : 'password';

                            button.textContent = isPassword
                                ? '🙈'
                                : '👁';

                            button.setAttribute(
                                'aria-label',
                                isPassword
                                    ? 'Sembunyikan kata sandi'
                                    : 'Tampilkan kata sandi'
                            );
                        }
                    );
                });
        }
    );
</script>
@endpush
