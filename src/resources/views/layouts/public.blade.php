<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi Ikan Air Tawar')</title>

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
            --shadow: 0 18px 45px rgba(8, 32, 50, .12);
            --shadow-sm: 0 10px 24px rgba(8, 32, 50, .08);
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
                radial-gradient(circle at top left, rgba(0, 180, 216, .12), transparent 32%),
                linear-gradient(180deg, #f6fcff 0%, #eef8fd 100%);
            line-height: 1.65;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        img {
            max-width: 100%;
            display: block;
        }

        .navbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255, 255, 255, .92);
            border-bottom: 1px solid rgba(229, 231, 235, .9);
            backdrop-filter: blur(16px);
        }

        .nav-inner {
            max-width: 1180px;
            margin: 0 auto;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 900;
            color: var(--navy);
            min-width: fit-content;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--blue), var(--cyan));
            color: white;
            display: grid;
            place-items: center;
            font-size: 22px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 180, 216, .25);
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
            color: var(--muted);
            font-weight: 800;
        }

        .nav-menu a {
            padding: 9px 12px;
            border-radius: 999px;
            transition: .2s ease;
        }

        .nav-menu a:hover {
            color: var(--blue);
            background: var(--cyan-soft);
        }

        .nav-menu form {
            margin: 0;
        }

        .nav-search {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 260px;
        }

        .nav-search input {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 11px 14px;
            outline: none;
            background: white;
        }

        .nav-search input:focus {
            border-color: var(--cyan);
            box-shadow: 0 0 0 4px rgba(0, 180, 216, .12);
        }

        .btn,
        .nav-search button,
        .logout-btn {
            border: none;
            border-radius: 999px;
            padding: 11px 18px;
            cursor: pointer;
            font-weight: 900;
            background: linear-gradient(135deg, var(--blue), var(--cyan));
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 10px 22px rgba(15, 76, 117, .22);
            transition: .2s ease;
            white-space: nowrap;
        }

        .btn:hover,
        .nav-search button:hover,
        .logout-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(15, 76, 117, .28);
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
                radial-gradient(circle at 85% 20%, rgba(0, 180, 216, .35), transparent 28%),
                linear-gradient(135deg, rgba(8, 32, 50, .96), rgba(15, 76, 117, .92));
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
            color: rgba(255,255,255,.86);
            font-size: 18px;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .hero-panel {
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .18);
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
            color: rgba(255,255,255,.84);
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
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .grid-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .grid-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
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
                radial-gradient(circle at 30% 20%, rgba(255, 255, 255, .65), transparent 25%),
                linear-gradient(135deg, #caf0f8, #90e0ef);
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
            grid-template-columns: repeat(3, minmax(0, 1fr));
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
            grid-template-columns: repeat(3, minmax(0, 1fr));
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
            grid-template-columns: repeat(2, minmax(0, 1fr));
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
            box-shadow: 0 0 0 4px rgba(0, 180, 216, .12);
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
                radial-gradient(circle at 80% 10%, rgba(0, 180, 216, .22), transparent 25%),
                linear-gradient(135deg, var(--navy), var(--blue));
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
            color: rgba(255,255,255,.82);
        }

        footer {
            margin-top: 60px;
            background: var(--navy);
            color: rgba(255,255,255,.82);
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
            border-top: 1px solid rgba(255,255,255,.14);
            color: rgba(255,255,255,.65);
            font-size: 14px;
        }

        @media (max-width: 1024px) {
            .nav-inner {
                align-items: flex-start;
                flex-wrap: wrap;
            }

            .nav-search {
                width: 100%;
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

            .nav-menu {
                justify-content: flex-start;
            }

            .content-box,
            .form-card,
            .table-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
@php
    $layoutSetting = \App\Models\WebSetting::query()->first();
    $authUser = auth()->user();
    $isAdmin = $authUser && ($authUser->is_admin || $authUser->hasRole('super_admin'));
@endphp

<nav class="navbar">
    <div class="nav-inner">
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-logo">
                @if($layoutSetting?->logo)
                    <img src="{{ asset('storage/' . $layoutSetting->logo) }}" alt="Logo">
                @else
                    🐟
                @endif
            </span>
            <span>{{ $layoutSetting?->site_name ?? 'Sistem Informasi Ikan Air Tawar' }}</span>
        </a>

        <div class="nav-menu">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('regions.index') }}">Wilayah</a>
            <a href="{{ route('categories.index') }}">Kategori</a>
            <a href="{{ route('fishes.index') }}">Ikan</a>

            @auth
                @if(! $isAdmin)
                    <a href="{{ route('creature-requests.create') }}">Ajukan Data</a>
                    <a href="{{ route('creature-requests.index') }}">Request Saya</a>
                @endif

                @if($isAdmin)
                    <a href="/admin">Admin</a>
                @endif

                <form action="{{ route('public.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            @else
                <a href="{{ route('public.login.form') }}">Login</a>
                <a href="{{ route('public.register.form') }}">Register</a>
            @endauth
        </div>

        <form action="{{ route('search') }}" method="GET" class="nav-search">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari ikan, habitat, wilayah...">
            <button type="submit">Cari</button>
        </form>
    </div>
</nav>

<main>
    @if(session('success'))
        <div class="container" style="padding-bottom: 0;">
            <div class="alert success">{{ session('success') }}</div>
        </div>
    @endif

    @yield('content')
</main>

<footer>
    <div class="footer-inner">
        <div class="footer-grid">
            <div>
                <span class="footer-title">{{ $layoutSetting?->site_name ?? 'Sistem Informasi Ikan Air Tawar' }}</span>
                <p>
                    {{ $layoutSetting?->footer_text ?? 'Website informasi ikan air tawar berbasis Laravel dan Filament untuk mendukung dokumentasi, edukasi, serta pengelolaan data secara terstruktur.' }}
                </p>
            </div>

            <div>
                <span class="footer-title">Navigasi</span>
                <div class="footer-links">
                    <a href="{{ route('home') }}">Beranda</a>
                    <a href="{{ route('regions.index') }}">Wilayah</a>
                    <a href="{{ route('categories.index') }}">Kategori</a>
                    <a href="{{ route('fishes.index') }}">Daftar Ikan</a>
                </div>
            </div>

            <div>
                <span class="footer-title">Kontak</span>
                <div class="footer-links">
                    @if($layoutSetting?->contact_email)
                        <span>Email: {{ $layoutSetting->contact_email }}</span>
                    @else
                        <span>Email: admin@ikan.test</span>
                    @endif

                    @if($layoutSetting?->contact_phone)
                        <span>Telepon: {{ $layoutSetting->contact_phone }}</span>
                    @endif

                    @if($layoutSetting?->address)
                        <span>{{ $layoutSetting->address }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            © {{ date('Y') }} {{ $layoutSetting?->site_name ?? 'Sistem Informasi Ikan Air Tawar' }}. All rights reserved.
        </div>
    </div>
</footer>
</body>
</html>
