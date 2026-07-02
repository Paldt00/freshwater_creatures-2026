@extends('layouts.public')

@section('title', 'Pencarian Ikan')

@section('content')
<div class="container">
    <section class="hero">
        <h1>Hasil Pencarian</h1>
        <p>
            @if($keyword)
                Menampilkan hasil untuk kata kunci: <strong>{{ $keyword }}</strong>
            @else
                Masukkan kata kunci untuk mencari ikan berdasarkan nama, nama ilmiah, habitat, wilayah, kategori, atau deskripsi.
            @endif
        </p>

        <form action="{{ route('search') }}" method="GET" class="nav-search" style="max-width: 620px;">
            <input type="text" name="q" value="{{ $keyword }}" placeholder="Contoh: arwana, Sumatra, konsumsi..." style="width: 100%;">
            <button type="submit">Cari</button>
        </form>
    </section>

    <div class="section-head">
        <div>
            <h2>Daftar Hasil</h2>
            <p>{{ $fishes->total() }} data ditemukan.</p>
        </div>
    </div>

    <div class="grid grid-3">
        @forelse($fishes as $fish)
            @include('partials.fish-card', ['fish' => $fish])
        @empty
            <div class="empty">Data ikan tidak ditemukan.</div>
        @endforelse
    </div>

    <div class="pagination">
        {{ $fishes->links() }}
    </div>
</div>
@endsection