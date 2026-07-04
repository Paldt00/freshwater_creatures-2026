@extends('layouts.public')

@section('title', $fish->name)

@section('content')
@php
    $habitatLocations = collect(preg_split('/[\/,;]/', (string) $fish->habitat))
        ->map(fn ($item) => trim($item))
        ->filter()
        ->unique()
        ->values();
@endphp

<div class="container">
    <div class="detail-layout">
        <aside class="detail-sidebar">
            <div class="card">
                <div class="thumb">
                    @if($fish->image)
                        <img src="{{ asset('storage/' . $fish->image) }}" alt="{{ $fish->name }}">
                    @else
                        🐟
                    @endif
                </div>

                <div class="card-body">
                    <h3 style="margin-bottom: 4px;">Informasi Singkat</h3>

                    <div class="info-list" style="margin-top: 14px;">
                        <div class="info-item">
                            <small>Nama Ilmiah</small>
                            <span>
                                @if($fish->scientific_name)
                                    <em>{{ $fish->scientific_name }}</em>
                                @else
                                    -
                                @endif
                            </span>
                        </div>

                        <div class="info-item">
                            <small>Wilayah</small>
                            @if($fish->region)
                                <a href="{{ route('regions.show', $fish->region->slug) }}">
                                    {{ $fish->region->name }}
                                </a>
                            @else
                                <span>-</span>
                            @endif
                        </div>

                        <div class="info-item">
                            <small>Kategori</small>
                            @if($fish->category)
                                <a href="{{ route('categories.show', $fish->category->slug) }}">
                                    {{ $fish->category->name }}
                                </a>
                            @else
                                <span>-</span>
                            @endif
                        </div>

                        <div class="info-item">
                            <small>Habitat</small>
                            <span>{{ $fish->habitat ?: '-' }}</span>
                        </div>

                        <div class="info-item">
                            <small>Makanan</small>
                            <span>{{ $fish->food ?: '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <article class="content-box">
            <div class="badge-row" style="margin-top: 0;">
                <span class="badge">🐟 Detail Ikan</span>

                @if($fish->region)
                    <span class="badge">🗺️ {{ $fish->region->name }}</span>
                @endif

                @if($fish->category)
                    <span class="badge">🏷️ {{ $fish->category->name }}</span>
                @endif
            </div>

            <h1 class="detail-title">{{ $fish->name }}</h1>

            @if($fish->scientific_name)
                <div class="latin">{{ $fish->scientific_name }}</div>
            @endif

            <div class="article-section">
                <h2>Deskripsi</h2>

                <div>
                    {!! $fish->description ?: '<p>Deskripsi belum tersedia.</p>' !!}
                </div>
            </div>

            <div class="article-section">
                <h2>Ciri-ciri</h2>

                <p>
                    {{ $fish->characteristics ?: 'Ciri-ciri belum tersedia.' }}
                </p>
            </div>

            <div class="article-section">
                <h2>Habitat</h2>

                <p>
                    {{ $fish->habitat ?: 'Habitat belum tersedia.' }}
                </p>

                @if($habitatLocations->count())
                    <div style="margin-top: 12px;">
                        @foreach($habitatLocations as $location)
                            <a
                                class="map-chip"
                                href="https://www.google.com/maps/search/?api=1&query={{ urlencode($location) }}"
                                target="_blank"
                                rel="noopener"
                            >
                                📍 {{ $location }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="article-section">
                <h2>Makanan</h2>

                <p>
                    {{ $fish->food ?: 'Informasi makanan belum tersedia.' }}
                </p>
            </div>

            @auth
                @if(! (auth()->user()->is_admin || auth()->user()->hasRole('super_admin')))
                    <div class="article-section">
                        <h2>Ajukan Perubahan Data</h2>
                        <p>
                            Menemukan informasi yang perlu diperbarui? Ajukan perubahan data agar dapat ditinjau oleh admin.
                        </p>

                        <a href="{{ route('creature-requests.create') }}" class="btn">
                            Ajukan Perubahan
                        </a>
                    </div>
                @endif
            @endauth
        </article>
    </div>

    <div class="section-head">
        <div>
            <h2>Ikan Terkait</h2>
            <p>Rekomendasi berdasarkan wilayah atau kategori yang sama.</p>
        </div>

        <a href="{{ route('fishes.index') }}" class="btn secondary">Lihat Semua Ikan</a>
    </div>

    <div class="grid grid-4">
        @forelse($relatedFishes as $relatedFish)
            @include('partials.fish-card', ['fish' => $relatedFish])
        @empty
            <div class="empty">Belum ada ikan terkait.</div>
        @endforelse
    </div>
</div>
@endsection
