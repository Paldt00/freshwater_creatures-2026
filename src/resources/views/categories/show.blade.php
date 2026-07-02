@extends('layouts.public')

@section('title', 'Kategori ' . $category->name)

@section('content')
<div class="container">
    <section class="hero">
        <h1>{{ $category->name }}</h1>
        <p>{{ $category->description ?? 'Informasi kategori ikan air tawar.' }}</p>
        <div class="badge-row">
            <span class="badge">{{ $category->fishes_count }} ikan terkait</span>
        </div>
    </section>

    <div class="section-head">
        <div>
            <h2>Ikan Kategori {{ $category->name }}</h2>
            <p>Daftar ikan air tawar yang termasuk pada kategori ini.</p>
        </div>
    </div>

    <div class="grid grid-3">
        @forelse($fishes as $fish)
            @include('partials.fish-card', ['fish' => $fish])
        @empty
            <div class="empty">Belum ada ikan pada kategori ini.</div>
        @endforelse
    </div>

    <div class="pagination">
        {{ $fishes->links() }}
    </div>
</div>
@endsection