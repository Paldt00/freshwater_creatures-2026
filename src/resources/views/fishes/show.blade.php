@extends('layouts.public')

@section('title', $fish->name)

@section('content')
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
                    <div class="info-list">
                        <div class="info-item">
                            <small>Wilayah</small>
                            <a href="{{ route('regions.show', $fish->region) }}">{{ $fish->region?->name ?? '-' }}</a>
                        </div>

                        <div class="info-item">
                            <small>Kategori</small>
                            <a href="{{ route('categories.show', $fish->category) }}">{{ $fish->category?->name ?? '-' }}</a>
                        </div>

                        <div class="info-item">
                            <small>Habitat</small>
                            <span>{{ $fish->habitat ?? '-' }}</span>
                        </div>

                        <div class="info-item">
                            <small>Makanan</small>
                            <span>{{ $fish->food ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <article class="content-box">
            <h1 class="detail-title">{{ $fish->name }}</h1>

            @if($fish->scientific_name)
                <div class="latin">{{ $fish->scientific_name }}</div>
            @endif

            <div class="badge-row">
                <span class="badge">{{ $fish->region?->name ?? '-' }}</span>
                <span class="badge">{{ $fish->category?->name ?? '-' }}</span>
            </div>

            <h2>Deskripsi</h2>
            <div>{!! $fish->description ?: '<p>Deskripsi belum tersedia.</p>' !!}</div>

            <h2>Ciri-ciri</h2>
            <p>{{ $fish->characteristics ?: 'Ciri-ciri belum tersedia.' }}</p>

            <h2>Habitat</h2>
            <p>{{ $fish->habitat ?: 'Habitat belum tersedia.' }}</p>

            <h2>Makanan</h2>
            <p>{{ $fish->food ?: 'Informasi makanan belum tersedia.' }}</p>
        </article>
    </div>

    <div class="section-head">
        <div>
            <h2>Ikan Terkait</h2>
            <p>Rekomendasi berdasarkan wilayah atau kategori yang sama.</p>
        </div>
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