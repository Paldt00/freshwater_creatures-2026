@extends('layouts.public')

@section('title', ($setting?->site_name ?? 'Sistem Informasi Ikan Air Tawar') . ' - Beranda')

@section('content')
<div class="container">
    <section class="hero">
        <h1>{{ $setting?->hero_title ?? 'Ensiklopedia Digital Ikan Air Tawar Indonesia' }}</h1>
        <p>{{ $setting?->hero_subtitle ?? 'Temukan informasi ikan air tawar berdasarkan wilayah, kategori, habitat, makanan, ciri-ciri, dan nama ilmiah.' }}</p>

        <div class="hero-actions">
            <a href="{{ route('fishes.index') }}" class="btn">Jelajahi Ikan</a>
            <a href="{{ route('regions.index') }}" class="btn secondary">Lihat Wilayah</a>
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

    <div class="section-head">
        <div>
            <h2>Wilayah Persebaran</h2>
            <p>Jelajahi ikan air tawar berdasarkan wilayah.</p>
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
            <p>Kelompok informasi ikan berdasarkan kategori.</p>
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
            <p>Data ikan yang ditampilkan sebagai referensi utama.</p>
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
</div>
@endsection