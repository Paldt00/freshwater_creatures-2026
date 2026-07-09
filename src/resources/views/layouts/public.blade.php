@php
    $layoutSetting = \App\Models\WebSetting::query()->first();

    $authUser = auth()
        ->guard('web')
        ->user();

    $isAdmin = $authUser instanceof \App\Models\User
        && (
            $authUser->is_admin
            || $authUser->hasRole('super_admin')
        );

    $siteName = $layoutSetting?->site_name
        ?? 'Sistem Informasi Ikan Air Tawar';

    $isHomeActive = request()->routeIs('home');

    $isRegionActive = request()->routeIs(
        'regions.*'
    );

    $isCategoryActive = request()->routeIs(
        'categories.*'
    );

    $isFishActive = request()->routeIs(
        'fishes.*'
    );

    $isRequestCreateActive = request()->routeIs(
        'creature-requests.create'
    );

    $isRequestIndexActive = request()->routeIs(
        'creature-requests.index'
    );

    $isLoginActive = request()->routeIs(
        'public.login.form'
    );

    $isRegisterActive = request()->routeIs(
        'public.register.form'
    );
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', $siteName)
    </title>

    <style>
        :root {
            --navy: #082032;
            --navy-2: #0b2b42;
            --blue: #0f4c75;
            --cyan: #00b4d8;
            --cyan-soft: #e6faff;
            --green: #16a34a;
            --red: #dc2626;
            --yellow: #d97706;
            --soft: #f4fbff;
            --white: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --border: #e5e7eb;
            --shadow:
                0 18px 45px rgba(8, 32, 50, .12);
            --shadow-sm:
                0 10px 24px rgba(8, 32, 50, .08);
            --radius: 22px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text);
            background:
                radial-gradient(
                    circle at top left,
                    rgba(0, 180, 216, .12),
                    transparent 32%
                ),
                linear-gradient(
                    180deg,
                    #f6fcff 0%,
                    #eef8fd 100%
                );
            line-height: 1.65;
        }

        body.menu-open {
            overflow: hidden;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        img {
            max-width: 100%;
            display: block;
        }

        button,
        input,
        select,
        textarea {
            font-family: inherit;
        }

        .navbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255, 255, 255, .94);
            border-bottom:
                1px solid rgba(229, 231, 235, .9);
            backdrop-filter: blur(16px);
        }

        .nav-inner {
            max-width: 1180px;
            margin: 0 auto;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 900;
            color: var(--navy);
            flex-shrink: 0;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            border-radius: 16px;
            background:
                linear-gradient(
                    135deg,
                    var(--blue),
                    var(--cyan)
                );
            color: white;
            display: grid;
            place-items: center;
            font-size: 22px;
            overflow: hidden;
            box-shadow:
                0 10px 20px rgba(0, 180, 216, .25);
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand-name {
            max-width: 250px;
            line-height: 1.25;
        }

        .nav-toggle {
            display: none;
            width: 44px;
            height: 44px;
            margin-left: auto;
            padding: 0;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: white;
            color: var(--navy);
            cursor: pointer;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 5px;
            box-shadow: var(--shadow-sm);
        }

        .nav-toggle-line {
            display: block;
            width: 21px;
            height: 2px;
            border-radius: 999px;
            background: currentColor;
            transition: .2s ease;
        }

        .nav-toggle.active
        .nav-toggle-line:nth-child(1) {
            transform:
                translateY(7px)
                rotate(45deg);
        }

        .nav-toggle.active
        .nav-toggle-line:nth-child(2) {
            opacity: 0;
        }

        .nav-toggle.active
        .nav-toggle-line:nth-child(3) {
            transform:
                translateY(-7px)
                rotate(-45deg);
        }

        .nav-content {
            flex: 1;
            min-width: 0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 14px;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
            flex-wrap: wrap;
            color: var(--muted);
            font-weight: 800;
        }

        .nav-menu a {
            padding: 9px 12px;
            border-radius: 999px;
            transition: .2s ease;
            white-space: nowrap;
        }

        .nav-menu a:hover {
            color: var(--blue);
            background: var(--cyan-soft);
        }

        .nav-menu a.active {
            color: white;
            background:
                linear-gradient(
                    135deg,
                    var(--blue),
                    var(--cyan)
                );
            box-shadow:
                0 8px 18px rgba(15, 76, 117, .22);
        }

        .nav-menu form {
            margin: 0;
        }

        .nav-search {
            width: 260px;
            min-width: 230px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-search input {
            width: 100%;
            min-width: 0;
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 11px 14px;
            outline: none;
            background: white;
        }

        .nav-search input:focus {
            border-color: var(--cyan);
            box-shadow:
                0 0 0 4px rgba(0, 180, 216, .12);
        }

        .btn,
        .nav-search button,
        .logout-btn {
            border: none;
            border-radius: 999px;
            padding: 11px 18px;
            cursor: pointer;
            font-weight: 900;
            background:
                linear-gradient(
                    135deg,
                    var(--blue),
                    var(--cyan)
                );
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow:
                0 10px 22px rgba(15, 76, 117, .22);
            transition: .2s ease;
            white-space: nowrap;
        }

        .btn:hover,
        .nav-search button:hover,
        .logout-btn:hover {
            transform: translateY(-1px);
            box-shadow:
                0 14px 28px rgba(15, 76, 117, .28);
        }

        .btn.secondary {
            background: white;
            color: var(--blue);
            border: 1px solid var(--border);
            box-shadow: none;
        }

        .btn.secondary:hover {
            background: var(--cyan-soft);
        }

        .logout-btn {
            background: var(--red);
            font-size: 14px;
            padding: 9px 14px;
            box-shadow: none;
        }

        .container {
            max-width: 1180px;
            margin: 0 auto;
            padding: 34px 20px;
        }

        .hero {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(
                    circle at 85% 20%,
                    rgba(0, 180, 216, .35),
                    transparent 28%
                ),
                linear-gradient(
                    135deg,
                    rgba(8, 32, 50, .96),
                    rgba(15, 76, 117, .92)
                );
            color: white;
            border-radius: 34px;
            padding: 56px 42px;
            box-shadow: var(--shadow);
        }

        .hero::after {
            content: "";
            position: absolute;
            inset: auto -70px -110px auto;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
        }

        .hero-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: 1.25fr .75fr;
            gap: 32px;
            align-items: center;
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .14);
            color: rgba(255, 255, 255, .9);
            font-weight: 900;
            margin-bottom: 16px;
        }

        .hero h1 {
            margin: 0 0 16px;
            max-width: 760px;
            font-size: clamp(34px, 5vw, 58px);
            line-height: 1.08;
            letter-spacing: -1px;
        }

        .hero p {
            max-width: 760px;
            margin: 0 0 28px;
            color: rgba(255, 255, 255, .86);
            font-size: 18px;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .hero-panel {
            background: rgba(255, 255, 255, .12);
            border:
                1px solid rgba(255, 255, 255, .18);
            border-radius: 28px;
            padding: 24px;
            backdrop-filter: blur(16px);
        }

        .hero-panel h3 {
            margin: 0 0 12px;
            color: white;
        }

        .hero-panel ul {
            margin: 0;
            padding-left: 20px;
            color: rgba(255, 255, 255, .84);
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 16px;
            margin: 46px 0 18px;
        }

        .section-head h2 {
            margin: 0;
            color: var(--navy);
            font-size: 31px;
            letter-spacing: -.4px;
        }

        .section-head p {
            margin: 5px 0 0;
            color: var(--muted);
        }

        .grid {
            display: grid;
            gap: 20px;
        }

        .grid-2 {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

        .grid-3 {
            grid-template-columns:
                repeat(3, minmax(0, 1fr));
        }

        .grid-4 {
            grid-template-columns:
                repeat(4, minmax(0, 1fr));
        }

        .card {
            background: rgba(255, 255, 255, .95);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: .2s ease;
        }

        a.card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow);
            border-color: rgba(0, 180, 216, .35);
        }

        .card-body {
            padding: 18px;
        }

        .card h3 {
            margin: 0 0 8px;
            color: var(--navy);
            line-height: 1.25;
        }

        .card p {
            margin: 0;
            color: var(--muted);
        }

        .thumb {
            width: 100%;
            height: 200px;
            background:
                radial-gradient(
                    circle at 30% 20%,
                    rgba(255, 255, 255, .65),
                    transparent 25%
                ),
                linear-gradient(
                    135deg,
                    #caf0f8,
                    #90e0ef
                );
            display: grid;
            place-items: center;
            color: var(--blue);
            font-size: 48px;
            overflow: hidden;
        }

        .thumb.small {
            height: 155px;
        }

        .thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .badge-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 12px 0;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-radius: 999px;
            padding: 5px 11px;
            background: #edf9ff;
            color: var(--blue);
            font-size: 13px;
            font-weight: 900;
        }

        .badge.pending {
            background: #fff7ed;
            color: var(--yellow);
        }

        .badge.approved {
            background: #ecfdf5;
            color: var(--green);
        }

        .badge.rejected {
            background: #fef2f2;
            color: var(--red);
        }

        .stats {
            display: grid;
            grid-template-columns:
                repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-top: 20px;
        }

        .stat {
            background: rgba(255, 255, 255, .96);
            border-radius: 22px;
            padding: 22px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        .stat strong {
            display: block;
            color: var(--navy);
            font-size: 34px;
            line-height: 1;
        }

        .stat span {
            color: var(--muted);
            font-weight: 800;
        }

        .feature-grid {
            display: grid;
            grid-template-columns:
                repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-top: 22px;
        }

        .feature-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 22px;
            padding: 22px;
            box-shadow: var(--shadow-sm);
        }

        .feature-icon {
            width: 46px;
            height: 46px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: var(--cyan-soft);
            color: var(--blue);
            font-size: 23px;
            margin-bottom: 12px;
        }

        .feature-card h3 {
            margin: 0 0 8px;
            color: var(--navy);
        }

        .feature-card p {
            margin: 0;
            color: var(--muted);
        }

        .detail-layout {
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 24px;
            align-items: start;
        }

        .detail-sidebar {
            position: sticky;
            top: 96px;
        }

        .detail-title {
            margin: 0 0 6px;
            font-size: clamp(34px, 4vw, 50px);
            color: var(--navy);
            line-height: 1.1;
        }

        .latin {
            color: var(--muted);
            font-style: italic;
            margin-bottom: 16px;
            font-size: 18px;
        }

        .info-list {
            display: grid;
            gap: 12px;
        }

        .info-item {
            padding: 14px;
            background: #f8fafc;
            border-radius: 15px;
            border: 1px solid var(--border);
        }

        .info-item small {
            color: var(--muted);
            display: block;
            font-weight: 900;
            margin-bottom: 4px;
        }

        .content-box,
        .form-card,
        .table-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 28px;
            box-shadow: var(--shadow);
        }

        .content-box h2,
        .form-card h2 {
            color: var(--navy);
            margin-top: 0;
        }

        .article-section {
            padding: 18px 0;
            border-bottom: 1px solid var(--border);
        }

        .article-section:last-child {
            border-bottom: none;
        }

        .map-chip {
            display: inline-flex;
            align-items: center;
            margin: 0 8px 8px 0;
            padding: 8px 12px;
            border-radius: 999px;
            background: var(--cyan-soft);
            color: var(--blue);
            font-weight: 900;
            font-size: 14px;
        }

        .auth-wrap {
            max-width: 520px;
            margin: 0 auto;
        }

        .form-grid {
            display: grid;
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .field {
            display: grid;
            gap: 8px;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        label {
            font-weight: 900;
            color: var(--navy);
        }

        input,
        select,
        textarea {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 12px 14px;
            outline: none;
            font: inherit;
            background: white;
        }

        textarea {
            resize: vertical;
            min-height: 112px;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--cyan);
            box-shadow:
                0 0 0 4px rgba(0, 180, 216, .12);
        }

        .error {
            color: var(--red);
            font-size: 14px;
            font-weight: 800;
        }

        .alert {
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 18px;
            font-weight: 800;
        }

        .alert.success {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .alert.danger {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .hidden {
            display: none;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 780px;
        }

        th,
        td {
            text-align: left;
            padding: 14px;
            border-bottom: 1px solid var(--border);
            vertical-align: top;
        }

        th {
            color: var(--navy);
            background: #f8fafc;
            font-size: 14px;
        }

        .empty {
            background: white;
            border: 1px dashed #bdd5e7;
            color: var(--muted);
            border-radius: 20px;
            padding: 36px;
            text-align: center;
        }

        .pagination {
            margin-top: 26px;
        }

        .pagination nav {
            display: flex;
            justify-content: center;
        }

        .cta {
            margin-top: 50px;
            background:
                radial-gradient(
                    circle at 80% 10%,
                    rgba(0, 180, 216, .22),
                    transparent 25%
                ),
                linear-gradient(
                    135deg,
                    var(--navy),
                    var(--blue)
                );
            color: white;
            border-radius: 30px;
            padding: 34px;
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: center;
            box-shadow: var(--shadow);
        }

        .cta h2 {
            margin: 0 0 8px;
        }

        .cta p {
            margin: 0;
            color: rgba(255, 255, 255, .82);
        }

        footer {
            margin-top: 60px;
            background: var(--navy);
            color: rgba(255, 255, 255, .82);
            padding: 42px 20px;
        }

        .footer-inner {
            max-width: 1180px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 24px;
        }

        .footer-title {
            color: white;
            font-weight: 900;
            margin-bottom: 10px;
            display: block;
        }

        .footer-links {
            display: grid;
            gap: 8px;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-bottom {
            margin-top: 28px;
            padding-top: 18px;
            border-top:
                1px solid rgba(255, 255, 255, .14);
            color: rgba(255, 255, 255, .65);
            font-size: 14px;
        }

        @media (max-width: 1100px) {
            .nav-inner {
                flex-wrap: wrap;
                gap: 12px;
            }

            .brand {
                max-width: calc(100% - 60px);
            }

            .brand-name {
                max-width: 420px;
            }

            .nav-toggle {
                display: inline-flex;
            }

            .nav-content {
                display: none;
                width: 100%;
                flex: none;
                flex-direction: column;
                align-items: stretch;
                gap: 14px;
                padding: 16px 0 4px;
                border-top: 1px solid var(--border);
            }

            .nav-content.open {
                display: flex;
            }

            .nav-menu {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
                gap: 6px;
            }

            .nav-menu a {
                width: 100%;
                padding: 11px 14px;
                border-radius: 14px;
            }

            .nav-menu form {
                width: 100%;
            }

            .logout-confirmation-wrapper {
                width: 100%;
            }

            .logout-btn {
                width: 100%;
                border-radius: 14px;
                padding: 11px 14px;
            }

            .nav-search {
                width: 100%;
                min-width: 0;
            }

            .hero-grid,
            .detail-layout {
                grid-template-columns: 1fr;
            }

            .detail-sidebar {
                position: static;
            }
        }

        @media (max-width: 780px) {
            .nav-inner {
                padding: 12px 14px;
            }

            .brand-name {
                max-width: 230px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .container {
                padding: 24px 14px;
            }

            .hero {
                padding: 38px 24px;
                border-radius: 26px;
            }

            .hero h1 {
                font-size: 34px;
            }

            .grid-2,
            .grid-3,
            .grid-4,
            .stats,
            .feature-grid,
            .form-grid,
            .footer-grid {
                grid-template-columns: 1fr;
            }

            .section-head,
            .cta {
                align-items: flex-start;
                flex-direction: column;
            }

            .content-box,
            .form-card,
            .table-card {
                padding: 20px;
            }
        }

        @media (max-width: 520px) {
            .brand-name {
                max-width: 180px;
                font-size: 14px;
            }

            .brand-logo,
            .nav-toggle {
                width: 42px;
                height: 42px;
            }

            .nav-search {
                display: grid;
                grid-template-columns: 1fr;
            }

            .nav-search button {
                width: 100%;
            }

            .hero {
                padding: 32px 20px;
            }

            .hero h1 {
                font-size: 30px;
            }

            .hero-actions .btn {
                width: 100%;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
<nav class="navbar">
    <div class="nav-inner">
        <a
            href="{{ route('home') }}"
            class="brand"
            aria-label="Kembali ke beranda"
        >
            <span class="brand-logo">
                @if($layoutSetting?->logo)
                    <img
                        src="{{
                            asset(
                                'storage/'
                                . $layoutSetting->logo
                            )
                        }}"
                        alt="Logo {{ $siteName }}"
                    >
                @else
                    🐟
                @endif
            </span>

            <span class="brand-name">
                {{ $siteName }}
            </span>
        </a>

        <button
            type="button"
            class="nav-toggle"
            id="navToggle"
            aria-label="Buka menu navigasi"
            aria-controls="navContent"
            aria-expanded="false"
        >
            <span class="nav-toggle-line"></span>
            <span class="nav-toggle-line"></span>
            <span class="nav-toggle-line"></span>
        </button>

        <div
            class="nav-content"
            id="navContent"
        >
            <div class="nav-menu">
                <a
                    href="{{ route('home') }}"
                    class="{{
                        $isHomeActive
                            ? 'active'
                            : ''
                    }}"
                >
                    Beranda
                </a>

                <a
                    href="{{ route('regions.index') }}"
                    class="{{
                        $isRegionActive
                            ? 'active'
                            : ''
                    }}"
                >
                    Wilayah
                </a>

                <a
                    href="{{ route('categories.index') }}"
                    class="{{
                        $isCategoryActive
                            ? 'active'
                            : ''
                    }}"
                >
                    Kategori
                </a>

                <a
                    href="{{ route('fishes.index') }}"
                    class="{{
                        $isFishActive
                            ? 'active'
                            : ''
                    }}"
                >
                    Ikan
                </a>

                @auth('web')
                    @if(! $isAdmin)
                        <a
                            href="{{
                                route(
                                    'creature-requests.create'
                                )
                            }}"
                            class="{{
                                $isRequestCreateActive
                                    ? 'active'
                                    : ''
                            }}"
                        >
                            Ajukan Data
                        </a>

                        <a
                            href="{{
                                route(
                                    'creature-requests.index'
                                )
                            }}"
                            class="{{
                                $isRequestIndexActive
                                    ? 'active'
                                    : ''
                            }}"
                        >
                            Pengajuan Saya
                        </a>
                    @endif

                    @if($isAdmin)
                        <a href="{{ url('/admin') }}">
                            Dashboard Admin
                        </a>
                    @endif

                    <x-logout-confirmation />
                @else
                    <a
                        href="{{
                            route(
                                'public.login.form'
                            )
                        }}"
                        class="{{
                            $isLoginActive
                                ? 'active'
                                : ''
                        }}"
                    >
                        Masuk
                    </a>

                    <a
                        href="{{
                            route(
                                'public.register.form'
                            )
                        }}"
                        class="{{
                            $isRegisterActive
                                ? 'active'
                                : ''
                        }}"
                    >
                        Daftar
                    </a>
                @endauth
            </div>

            <form
                action="{{ route('search') }}"
                method="GET"
                class="nav-search"
                role="search"
            >
                <input
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari ikan, habitat, wilayah..."
                    aria-label="Kata kunci pencarian"
                >

                <button type="submit">
                    Cari
                </button>
            </form>
        </div>
    </div>
</nav>

<main>
    @if(session('success'))
        <div
            class="container"
            style="padding-bottom: 0;"
        >
            <div
                class="alert success"
                role="alert"
            >
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div
            class="container"
            style="padding-bottom: 0;"
        >
            <div
                class="alert danger"
                role="alert"
            >
                {{ session('error') }}
            </div>
        </div>
    @endif

    @yield('content')
</main>

<footer>
    <div class="footer-inner">
        <div class="footer-grid">
            <div>
                <span class="footer-title">
                    {{ $siteName }}
                </span>

                <p>
                    {{
                        $layoutSetting?->footer_text
                        ?? 'Website informasi ikan air tawar untuk mendukung dokumentasi, edukasi, dan pengelolaan data secara terstruktur.'
                    }}
                </p>
            </div>

            <div>
                <span class="footer-title">
                    Navigasi
                </span>

                <div class="footer-links">
                    <a href="{{ route('home') }}">
                        Beranda
                    </a>

                    <a href="{{ route('regions.index') }}">
                        Wilayah
                    </a>

                    <a href="{{ route('categories.index') }}">
                        Kategori
                    </a>

                    <a href="{{ route('fishes.index') }}">
                        Daftar Ikan
                    </a>

                    @auth('web')
                        @if(! $isAdmin)
                            <a
                                href="{{
                                    route(
                                        'creature-requests.index'
                                    )
                                }}"
                            >
                                Pengajuan Saya
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <div>
                <span class="footer-title">
                    Kontak
                </span>

                <div class="footer-links">
                    @if($layoutSetting?->contact_email)
                        <a
                            href="mailto:{{
                                $layoutSetting->contact_email
                            }}"
                        >
                            Email:
                            {{ $layoutSetting->contact_email }}
                        </a>
                    @else
                        <span>
                            Email: admin@ikan.test
                        </span>
                    @endif

                    @if($layoutSetting?->contact_phone)
                        <a
                            href="tel:{{
                                preg_replace(
                                    '/[^0-9+]/',
                                    '',
                                    $layoutSetting->contact_phone
                                )
                            }}"
                        >
                            Telepon:
                            {{ $layoutSetting->contact_phone }}
                        </a>
                    @endif

                    @if($layoutSetting?->address)
                        <span>
                            {{ $layoutSetting->address }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            © {{ date('Y') }} {{ $siteName }}.
            Hak cipta dilindungi.
        </div>
    </div>
</footer>

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {
            const navToggle =
                document.getElementById(
                    'navToggle'
                );

            const navContent =
                document.getElementById(
                    'navContent'
                );

            if (!navToggle || !navContent) {
                return;
            }

            function closeNavigation() {
                navToggle.classList.remove(
                    'active'
                );

                navContent.classList.remove(
                    'open'
                );

                document.body.classList.remove(
                    'menu-open'
                );

                navToggle.setAttribute(
                    'aria-expanded',
                    'false'
                );

                navToggle.setAttribute(
                    'aria-label',
                    'Buka menu navigasi'
                );
            }

            function openNavigation() {
                navToggle.classList.add(
                    'active'
                );

                navContent.classList.add(
                    'open'
                );

                document.body.classList.add(
                    'menu-open'
                );

                navToggle.setAttribute(
                    'aria-expanded',
                    'true'
                );

                navToggle.setAttribute(
                    'aria-label',
                    'Tutup menu navigasi'
                );
            }

            navToggle.addEventListener(
                'click',
                function () {
                    if (
                        navContent.classList.contains(
                            'open'
                        )
                    ) {
                        closeNavigation();

                        return;
                    }

                    openNavigation();
                }
            );

            navContent
                .querySelectorAll('a')
                .forEach(function (link) {
                    link.addEventListener(
                        'click',
                        closeNavigation
                    );
                });

            document.addEventListener(
                'keydown',
                function (event) {
                    if (event.key === 'Escape') {
                        closeNavigation();
                    }
                }
            );

            window.addEventListener(
                'resize',
                function () {
                    if (
                        window.innerWidth > 1100
                    ) {
                        closeNavigation();
                    }
                }
            );
        }
    );
</script>

<script
    src="{{ asset('js/csrf-refresh.js') }}"
    data-csrf-refresh-url="{{ route('csrf.refresh') }}"
></script>

@stack('scripts')
</body>
</html>
