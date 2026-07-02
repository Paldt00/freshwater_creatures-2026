@extends('layouts.public')

@section('title', 'Daftar Ikan')

@section('content')
<div class="container">
    <div class="section-head">
        <div>
            <h2>Daftar Ikan Air Tawar</h2>
            <p>Kumpulan data ikan air tawar yang telah dipublikasikan.</p>
        </div>
    </div>

    <div class="grid grid-3">
        @forelse($fishes as $fish)
            @include('partials.fish-card', ['fish' => $fish])
        @empty
            <div class="empty">Belum ada data ikan.</div>
        @endforelse
    </div>

    <div class="pagination">
        {{ $fishes->links() }}
    </div>
</div>
@endsection