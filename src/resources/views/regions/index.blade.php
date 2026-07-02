@extends('layouts.public')

@section('title', 'Daftar Wilayah')

@section('content')
<div class="container">
    <div class="section-head">
        <div>
            <h2>Daftar Wilayah</h2>
            <p>Pilih wilayah untuk melihat daftar ikan yang terkait.</p>
        </div>
    </div>

    <div class="grid grid-4">
        @forelse($regions as $region)
            @include('partials.region-card', ['region' => $region])
        @empty
            <div class="empty">Belum ada data wilayah.</div>
        @endforelse
    </div>

    <div class="pagination">
        {{ $regions->links() }}
    </div>
</div>
@endsection