@extends('layouts.public')

@section('title', 'Daftar Kategori')

@section('content')
<div class="container">
    <div class="section-head">
        <div>
            <h2>Daftar Kategori</h2>
            <p>Pilih kategori untuk melihat kelompok ikan terkait.</p>
        </div>
    </div>

    <div class="grid grid-4">
        @forelse($categories as $category)
            @include('partials.category-card', ['category' => $category])
        @empty
            <div class="empty">Belum ada data kategori.</div>
        @endforelse
    </div>

    <div class="pagination">
        {{ $categories->links() }}
    </div>
</div>
@endsection