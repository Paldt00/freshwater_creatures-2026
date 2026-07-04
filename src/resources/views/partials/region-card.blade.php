<a href="{{ route('regions.show', $region->slug) }}" class="card">
    <div class="thumb small">
        @if($region->image)
            <img src="{{ asset('storage/' . $region->image) }}" alt="{{ $region->name }}">
        @else
            🗺️
        @endif
    </div>

    <div class="card-body">
        <h3>{{ $region->name }}</h3>

        <div class="badge-row">
            <span class="badge">🐟 {{ $region->fishes_count ?? 0 }} ikan</span>
        </div>

        <p>
            {{ \Illuminate\Support\Str::limit($region->description, 120) ?: 'Deskripsi wilayah belum tersedia.' }}
        </p>
    </div>
</a>
