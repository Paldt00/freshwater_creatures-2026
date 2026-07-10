@extends('layouts.public')

@section('title', 'Daftar')

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
        position: relative;
        width: min(100%, 590px);
        padding: 34px;
        border: 1px solid rgba(255, 255, 255, .78);
        border-radius: 28px;
        background: rgba(255, 255, 255, .88);
        box-shadow:
            0 28px 70px rgba(8, 32, 50, .20);
        backdrop-filter: blur(24px);
    }

    .auth-close-button {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 42px;
        height: 42px;
        display: grid;
        place-items: center;
        border: 1px solid rgba(209, 213, 219, .9);
        border-radius: 50%;
        color: var(--muted);
        background: rgba(255, 255, 255, .86);
        box-shadow:
            0 8px 22px rgba(8, 32, 50, .10);
        text-decoration: none;
        font-size: 25px;
        font-weight: 500;
        line-height: 1;
        transition:
            color .2s ease,
            background-color .2s ease,
            border-color .2s ease,
            transform .2s ease,
            box-shadow .2s ease;
    }

    .auth-close-button:hover {
        color: #ffffff;
        background: #dc2626;
        border-color: #dc2626;
        transform: rotate(90deg);
        box-shadow:
            0 12px 26px rgba(220, 38, 38, .24);
    }

    .auth-close-button:focus-visible {
        outline: 3px solid rgba(14, 165, 233, .42);
        outline-offset: 3px;
    }

    .auth-panel-header {
        margin-bottom: 24px;
        padding-right: 48px;
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

    .password-toggle:focus-visible {
        outline: 3px solid rgba(14, 165, 233, .35);
        outline-offset: 2px;
    }

    .password-requirement {
        margin: 0;
        color: var(--muted);
        font-size: 13px;
    }

    .auth-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 4px;
    }

    .auth-actions .btn {
        min-width: 120px;
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

        .auth-close-button {
            top: 16px;
            right: 16px;
            width: 38px;
            height: 38px;
            font-size: 22px;
        }

        .auth-panel-header {
            padding-right: 42px;
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
        <a
            href="{{ route('home') }}"
            class="auth-close-button"
            aria-label="Tutup halaman daftar dan kembali ke beranda"
            title="Kembali ke beranda"
        >
            &times;
        </a>

        <div class="auth-icon">
            👤
        </div>

        <div class="auth-panel-header">
            <h1>Daftar Akun</h1>

            <p>
                Buat akun untuk ikut berkontribusi dalam pembaruan
                data ikan air tawar.
            </p>
        </div>

        @if($errors->any())
            <div class="alert danger">
                Data pendaftaran belum sesuai. Silakan periksa kembali.
            </div>
        @endif

        <form
            action="{{ route('public.register') }}"
            method="POST"
            class="auth-form"
        >
            @csrf

            <div class="field">
                <label for="name">
                    Nama
                </label>

                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name') }}"
                    placeholder="Masukkan nama lengkap"
                    autocomplete="name"
                    autofocus
                    required
                >

                @error('name')
                    <span class="error">
                        {{ $message }}
                    </span>
                @enderror
            </div>

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
                        autocomplete="new-password"
                        minlength="8"
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

                <p class="password-requirement">
                    Gunakan minimal 8 karakter.
                </p>

                @error('password')
                    <span class="error">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="field">
                <label for="password_confirmation">
                    Konfirmasi Kata Sandi
                </label>

                <div class="auth-password-wrapper">
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        data-password-toggle="password_confirmation"
                        aria-label="Tampilkan konfirmasi kata sandi"
                    >
                        👁
                    </button>
                </div>
            </div>

            <div class="auth-actions">
                <button
                    type="submit"
                    class="btn"
                >
                    Daftar
                </button>

                <a
                    href="{{ route('public.login.form') }}"
                    class="btn secondary"
                >
                    Sudah Punya Akun
                </a>
            </div>
        </form>

        <div class="auth-help">
            Sudah mempunyai akun?

            <a href="{{ route('public.login.form') }}">
                Masuk sekarang
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
