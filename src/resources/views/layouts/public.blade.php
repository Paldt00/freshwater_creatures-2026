<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi Ikan Air Tawar')</title>

    <style>
        :root {
            --navy: #082032;
            --blue: #0f4c75;
            --cyan: #00b4d8;
            --green: #2dc653;
            --red: #ef4444;
            --yellow: #f59e0b;
            --soft: #f4fbff;
            --text: #1f2937;
            --muted: #6b7280;
            --white: #ffffff;
            --border: #e5e7eb;
            --shadow: 0 12px 35px rgba(8, 32, 50, .12);
            --radius: 20px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text);
            background: var(--soft);
            line-height: 1.6;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .navbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255, 255, 255, .95);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(14px);
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
            gap: 10px;
            font-weight: 800;
            color: var(--navy);
        }

        .brand-logo {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--blue), var(--cyan));
            color: white;
            display: grid;
            place-items: center;
            font-size: 22px;
            overflow: hidden;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 14px;
            color: var(--muted);
            font-weight: 700;
            flex-wrap: wrap;
        }

        .nav-menu a:hover {
            color: var(--blue);
        }

        .nav-search {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-search input {
            width: 220px;
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 10px 14px;
            outline: none;
        }

        .nav-search button,
        .btn,
        .logout-btn {
            border: none;
            border-radius: 999px;
            padding: 10px 16px;
            cursor: pointer;
            font-weight: 800;
            background: var(--blue);
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn.secondary {
            background: white;
            color: var(--blue);
            border: 1px solid var(--border);
        }

        .logout-btn {
            background: var(--red);
            font-size: 14px;
        }

        .container {
            max-width: 1180px;
            margin: 0 auto;
            padding: 34px 20px;
        }

        .hero {
            background:
                linear-gradient(135deg, rgba(8, 32, 50, .90), rgba(15, 76, 117, .82)),
                var(--navy);
            color: white;
            border-radius: 32px;
            padding: 62px 40px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .hero h1 {
            margin: 0 0 14px;
            max-width: 780px;
            font-size: clamp(34px, 5vw, 58px);
            line-height: 1.08;
        }

        .hero p {
            max-width: 780px;
            margin: 0 0 28px;
            color: rgba(255,255,255,.85);
            font-size: 18px;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 16px;
            margin: 44px 0 18px;
        }

        .section-head h2 {
            margin: 0;
            color: var(--navy);
            font-size: 30px;
        }

        .section-head p {
            margin: 4px 0 0;
            color: var(--muted);
        }

        .grid {
            display: grid;
            gap: 20px;
        }

        .grid-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .grid-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(8, 32, 50, .08);
        }

        .card-body {
            padding: 18px;
        }

        .card h3 {
            margin: 0 0 8px;
            color: var(--navy);
        }

        .card p {
            margin: 0;
            color: var(--muted);
        }

        .thumb {
            width: 100%;
            height: 190px;
            background: linear-gradient(135deg, #caf0f8, #ade8f4);
            display: grid;
            place-items: center;
            color: var(--blue);
            font-size: 44px;
            overflow: hidden;
        }

        .thumb.small {
            height: 150px;
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
            border-radius: 999px;
            padding: 5px 10px;
            background: #edf9ff;
            color: var(--blue);
            font-size: 13px;
            font-weight: 800;
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
            margin-top: 24px;
        }

        .stat {
            background: white;
            border-radius: 20px;
            padding: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 8px 24px rgba(8, 32, 50, .08);
        }

        .stat strong {
            display: block;
            color: var(--navy);
            font-size: 32px;
            line-height: 1;
        }

        .stat span {
            color: var(--muted);
            font-weight: 700;
        }

        .detail-layout {
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 24px;
        }

        .detail-sidebar {
            position: sticky;
            top: 92px;
            align-self: start;
        }

        .detail-title {
            margin: 0 0 6px;
            font-size: 42px;
            color: var(--navy);
        }

        .latin {
            color: var(--muted);
            font-style: italic;
            margin-bottom: 18px;
        }

        .info-list {
            display: grid;
            gap: 12px;
        }

        .info-item {
            padding: 14px;
            background: #f8fafc;
            border-radius: 14px;
            border: 1px solid var(--border);
        }

        .info-item small {
            color: var(--muted);
            display: block;
            font-weight: 800;
        }

        .content-box,
        .form-card,
        .table-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 26px;
            box-shadow: var(--shadow);
        }

        .content-box h2,
        .form-card h2 {
            color: var(--navy);
            margin-top: 0;
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
            font-weight: 800;
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
            min-height: 110px;
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
            font-weight: 700;
        }

        .alert {
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 18px;
            font-weight: 700;
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

        td {
            color: var(--text);
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

        footer {
            margin-top: 60px;
            background: var(--navy);
            color: rgba(255,255,255,.82);
            padding: 34px 20px;
        }

        footer .footer-inner {
            max-width: 1180px;
            margin: 0 auto;
        }

        @media (max-width: 900px) {
            .nav-inner,
            .nav-menu,
            .nav-search {
                flex-wrap: wrap;
            }

            .nav-inner {
                align-items: flex-start;
            }

            .nav-search input {
                width: 100%;
            }

            .grid-3,
            .grid-4,
            .stats,
            .detail-layout,
            .form-grid {
                grid-template-columns: 1fr;
            }

            .detail-sidebar {
                position: static;
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
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari ikan...">
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
        <strong>{{ $layoutSetting?->site_name ?? 'Sistem Informasi Ikan Air Tawar' }}</strong>
        <p>{{ $layoutSetting?->footer_text ?? 'Website informasi ikan air tawar berbasis Laravel dan Filament.' }}</p>

        @if($layoutSetting?->contact_email)
            <p>Kontak: {{ $layoutSetting->contact_email }}</p>
        @endif
    </div>
</footer>
</body>
</html>
