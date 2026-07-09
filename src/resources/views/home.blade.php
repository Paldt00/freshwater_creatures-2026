@extends('layouts.public')

@section(
    'title',
    ($setting?->site_name ?? 'Sistem Informasi Ikan Air Tawar') . ' - Beranda'
)

@section('content')
@php
    $bannerUrl = $setting?->banner_image
        ? asset('storage/' . $setting->banner_image)
        : null;

    $isAdmin = auth()->check()
        && (
            auth()->user()->is_admin
            || auth()->user()->hasRole('super_admin')
        );
@endphp

<style>
    .hero-banner-panel {
        min-height: 330px;
        padding: 18px;
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.20);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        backdrop-filter: blur(14px);
    }

    .hero-banner-panel img {
        width: 100%;
        height: 100%;
        max-height: 360px;
        object-fit: contain;
        border-radius: 20px;
        background: white;
        box-shadow: 0 18px 38px rgba(0, 0, 0, 0.20);
    }

    .hero-banner-placeholder {
        width: 100%;
        min-height: 290px;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: rgba(255, 255, 255, 0.10);
        color: rgba(255, 255, 255, 0.88);
        padding: 24px;
    }

    .hero-banner-placeholder span {
        font-size: 70px;
        line-height: 1;
        margin-bottom: 16px;
    }

    .hero-banner-placeholder strong {
        color: white;
        font-size: 20px;
    }

    .hero-banner-placeholder p {
        margin: 8px 0 0;
        font-size: 14px;
    }

    @media (max-width: 1024px) {
        .hero-banner-panel {
            min-height: 280px;
        }

        .hero-banner-panel img {
            max-height: 320px;
        }
    }

    @media (max-width: 780px) {
        .hero-banner-panel {
            min-height: 230px;
            padding: 12px;
        }

        .hero-banner-panel img {
            max-height: 280px;
            border-radius: 16px;
        }

        .hero-banner-placeholder {
            min-height: 210px;
        }
    }
</style>

<div class="container">
    <section class="hero">
        <div class="hero-grid">
            <div>
                <div class="hero-kicker">
                    🐟 Ensiklopedia Ikan Air Tawar
                </div>

                <h1>
                    {{
                        $setting?->hero_title
                        ?? 'Pusat Informasi Ikan Air Tawar'
                    }}
                </h1>

                <p>
                    {{
                        $setting?->hero_subtitle
                        ?? 'Jelajahi informasi ikan air tawar berdasarkan wilayah, kategori, habitat, makanan, ciri-ciri, nama ilmiah, status kelestarian, dan biogeografi secara terstruktur.'
                    }}
                </p>

                <div class="hero-actions">
                    <a
                        href="{{ route('fishes.index') }}"
                        class="btn"
                    >
                        Jelajahi Ikan
                    </a>

                    <a
                        href="{{ route('regions.index') }}"
                        class="btn secondary"
                    >
                        Lihat Wilayah
                    </a>

                    @auth
                        @if(! $isAdmin)
                            <a
                                href="{{ route('creature-requests.create') }}"
                                class="btn secondary"
                            >
                                Ajukan Data
                            </a>
                        @endif
                    @else
                        <a
                            href="{{ route('public.register.form') }}"
                            class="btn secondary"
                        >
                            Gabung Sekarang
                        </a>
                    @endauth
                </div>
            </div>

            <div class="hero-banner-panel">
                @if($bannerUrl)
                    <img
                        src="{{ $bannerUrl }}"
                        alt="Banner {{ $setting?->site_name ?? 'Freshwater Creatures' }}"
                    >
                @else
                    <div class="hero-banner-placeholder">
                        <span>🐟</span>

                        <strong>
                            Freshwater Creatures
                        </strong>

                        <p>
                            Gambar banner dapat diunggah melalui Pengaturan Website.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="stat">
            <strong>
                {{ $stats['fish_count'] }}
            </strong>

            <span>
                Total Ikan
            </span>
        </div>

        <div class="stat">
            <strong>
                {{ $stats['region_count'] }}
            </strong>

            <span>
                Total Wilayah
            </span>
        </div>

        <div class="stat">
            <strong>
                {{ $stats['category_count'] }}
            </strong>

            <span>
                Total Kategori
            </span>
        </div>
    </section>

    <section class="feature-grid">
        <div class="feature-card">
            <div class="feature-icon">
                🗺️
            </div>

            <h3>
                Berbasis Wilayah
            </h3>

            <p>
                Informasi ikan disusun berdasarkan wilayah persebaran agar lebih mudah dipahami.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                🔎
            </div>

            <h3>
                Mudah Dicari
            </h3>

            <p>
                Pengunjung dapat mencari ikan berdasarkan nama, nama ilmiah, habitat, kategori, wilayah, status kelestarian, dan biogeografi.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                ✅
            </div>

            <h3>
                Terverifikasi
            </h3>

            <p>
                Pengajuan data dari pengguna diproses melalui persetujuan administrator.
            </p>
        </div>
    </section>

    <div class="section-head">
        <div>
            <h2>
                Wilayah Persebaran
            </h2>

            <p>
                Jelajahi data ikan air tawar berdasarkan wilayah.
            </p>
        </div>

        <a
            href="{{ route('regions.index') }}"
            class="btn secondary"
        >
            Lihat Semua
        </a>
    </div>

    <div class="grid grid-4">
        @forelse($regions as $region)
            @include('partials.region-card', [
                'region' => $region,
            ])
        @empty
            <div class="empty">
                Belum ada data wilayah.
            </div>
        @endforelse
    </div>

    <div class="section-head">
        <div>
            <h2>
                Kategori Ikan
            </h2>

            <p>
                Temukan ikan berdasarkan kelompok atau kategori.
            </p>
        </div>

        <a
            href="{{ route('categories.index') }}"
            class="btn secondary"
        >
            Lihat Semua
        </a>
    </div>

    <div class="grid grid-4">
        @forelse($categories as $category)
            @include('partials.category-card', [
                'category' => $category,
            ])
        @empty
            <div class="empty">
                Belum ada data kategori.
            </div>
        @endforelse
    </div>

    <div class="section-head">
        <div>
            <h2>
                Ikan Unggulan
            </h2>

            <p>
                Daftar ikan yang dipilih sebagai referensi utama.
            </p>
        </div>

        <a
            href="{{ route('fishes.index') }}"
            class="btn secondary"
        >
            Lihat Semua
        </a>
    </div>

    <div class="grid grid-3">
        @forelse(
            $featuredFishes->count()
                ? $featuredFishes
                : $latestFishes
            as $fish
        )
            @include('partials.fish-card', [
                'fish' => $fish,
            ])
        @empty
            <div class="empty">
                Belum ada data ikan.
            </div>
        @endforelse
    </div>

    @guest
        <section class="cta">
            <div>
                <h2>
                    Punya informasi ikan air tawar lainnya?
                </h2>

                <p>
                    Pengguna terdaftar dapat mengajukan penambahan atau perubahan data yang selanjutnya akan melalui proses pemeriksaan dan verifikasi.
                </p>
            </div>

            <a
                href="{{ route('public.register.form') }}"
                class="btn"
            >
                Daftar Akun
            </a>
        </section>
    @else
        @if(! $isAdmin)
            <section class="cta">
                <div>
                    <h2>
                        Punya informasi ikan air tawar lainnya?
                    </h2>

                    <p>
                        Ajukan penambahan atau perubahan data ikan agar dapat diperiksa dan diverifikasi sebelum dipublikasikan.
                    </p>
                </div>

                <a
                    href="{{ route('creature-requests.create') }}"
                    class="btn"
                >
                    Ajukan Data
                </a>
            </section>
        @endif
    @endguest
</div>
@endsection
