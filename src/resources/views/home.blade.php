@extends('layouts.public')

@section('title', ($setting?->site_name ?? 'Sistem Informasi Ikan Air Tawar') . ' - Beranda')

@section('content')
<div class="container">
    <section class="hero">
        <div class="hero-grid">
            <div>
                <div class="hero-kicker">
                    🐟 Ensiklopedia Ikan Air Tawar
                </div>

                <h1>
                    {{ $setting?->hero_title ?? 'Pusat Informasi Ikan Air Tawar Indonesia' }}
                </h1>

                <p>
                    {{ $setting?->hero_subtitle ?? 'Jelajahi informasi ikan air tawar berdasarkan wilayah, kategori, habitat, makanan, ciri-ciri, dan nama ilmiah secara terstruktur.' }}
                </p>

                <div class="hero-actions">
                    <a href="{{ route('fishes.index') }}" class="btn">Jelajahi Ikan</a>
                    <a href="{{ route('regions.index') }}" class="btn secondary">Lihat Wilayah</a>

                    @auth
                        @if(! (auth()->user()->is_admin || auth()->user()->hasRole('super_admin')))
                            <a href="{{ route('creature-requests.create') }}" class="btn secondary">Ajukan Data</a>
                        @endif
                    @else
                        <a href="{{ route('public.register.form') }}" class="btn secondary">Gabung Sekarang</a>
                    @endauth
                </div>
            </div>

            <div class="hero-panel">
                <h3>Fitur Utama</h3>
                <ul>
                    <li>Data ikan berdasarkan wilayah persebaran.</li>
                    <li>Kategori ikan konsumsi, hias, dan endemik.</li>
                    <li>Pencarian berdasarkan nama, habitat, dan wilayah.</li>
                    <li>Pengajuan data baru melalui verifikasi admin.</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="stat">
            <strong>{{ $stats['fish_count'] }}</strong>
            <span>Total Ikan</span>
        </div>

        <div class="stat">
            <strong>{{ $stats['region_count'] }}</strong>
            <span>Total Wilayah</span>
        </div>

        <div class="stat">
            <strong>{{ $stats['category_count'] }}</strong>
            <span>Total Kategori</span>
        </div>
    </section>

    <section class="feature-grid">
        <div class="feature-card">
            <div class="feature-icon">🗺️</div>
            <h3>Berbasis Wilayah</h3>
            <p>Informasi ikan disusun berdasarkan wilayah persebaran agar lebih mudah dipahami.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">🔎</div>
            <h3>Mudah Dicari</h3>
            <p>Pengunjung dapat mencari ikan berdasarkan nama, nama ilmiah, habitat, kategori, atau wilayah.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">✅</div>
            <h3>Terverifikasi</h3>
            <p>Pengajuan data dari pengguna diproses melalui persetujuan administrator.</p>
        </div>
    </section>

    <div class="section-head">
        <div>
            <h2>Wilayah Persebaran</h2>
            <p>Jelajahi data ikan air tawar berdasarkan wilayah.</p>
        </div>

        <a href="{{ route('regions.index') }}" class="btn secondary">Lihat Semua</a>
    </div>

    <div class="grid grid-4">
        @forelse($regions as $region)
            @include('partials.region-card', ['region' => $region])
        @empty
            <div class="empty">Belum ada data wilayah.</div>
        @endforelse
    </div>

    <div class="section-head">
        <div>
            <h2>Kategori Ikan</h2>
            <p>Temukan ikan berdasarkan kelompok atau kategori.</p>
        </div>

        <a href="{{ route('categories.index') }}" class="btn secondary">Lihat Semua</a>
    </div>

    <div class="grid grid-4">
        @forelse($categories as $category)
            @include('partials.category-card', ['category' => $category])
        @empty
            <div class="empty">Belum ada data kategori.</div>
        @endforelse
    </div>

    <div class="section-head">
        <div>
            <h2>Ikan Unggulan</h2>
            <p>Daftar ikan yang dipilih sebagai referensi utama.</p>
        </div>

        <a href="{{ route('fishes.index') }}" class="btn secondary">Lihat Semua</a>
    </div>

    <div class="grid grid-3">
        @forelse($featuredFishes->count() ? $featuredFishes : $latestFishes as $fish)
            @include('partials.fish-card', ['fish' => $fish])
        @empty
            <div class="empty">Belum ada data ikan.</div>
        @endforelse
    </div>

    <section class="cta">
        <div>
            <h2>Punya informasi ikan air tawar?</h2>
            <p>Pengguna terdaftar dapat mengajukan penambahan atau perubahan data, lalu admin akan melakukan verifikasi.</p>
        </div>

        @auth
            @if(! (auth()->user()->is_admin || auth()->user()->hasRole('super_admin')))
                <a href="{{ route('creature-requests.create') }}" class="btn">Ajukan Data</a>
            @else
                <a href="/admin" class="btn">Buka Admin</a>
            @endif
        @else
            <a href="{{ route('public.register.form') }}" class="btn">Daftar Akun</a>
        @endauth
    </section>
</div>
@endsection
