<a href="{{ route('categories.show', $category->slug) }}" class="card">
    <div class="thumb small">
        @if($category->image)
            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
        @else
            🏷️
        @endif
    </div>

    <div class="card-body">
        <h3>{{ $category->name }}</h3>

        <div class="badge-row">
            <span class="badge">🐟 {{ $category->fishes_count ?? 0 }} ikan</span>
        </div>

        <p>
            {{ \Illuminate\Support\Str::limit($category->description, 120) ?: 'Deskripsi kategori belum tersedia.' }}
        </p>
    </div>
</a>
