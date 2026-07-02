@extends('layouts.public')

@section('title', 'Wilayah ' . $region->name)

@section('content')
<div class="container">
    <section class="hero">
        <h1>{{ $region->name }}</h1>
        <p>{{ $region->description ?? 'Informasi wilayah persebaran ikan air tawar.' }}</p>
        <div class="badge-row">
            <span class="badge">{{ $region->fishes_count }} ikan terkait</span>
        </div>
    </section>

    <div class="section-head">
        <div>
            <h2>Ikan di Wilayah {{ $region->name }}</h2>
            <p>Daftar ikan air tawar yang berelasi dengan wilayah ini.</p>
        </div>
    </div>

    <div class="grid grid-3">
        @forelse($fishes as $fish)
            @include('partials.fish-card', ['fish' => $fish])
        @empty
            <div class="empty">Belum ada ikan pada wilayah ini.</div>
        @endforelse
    </div>

    <div class="pagination">
        {{ $fishes->links() }}
    </div>
</div>
@endsection