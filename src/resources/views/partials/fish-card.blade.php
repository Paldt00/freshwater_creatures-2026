<a href="{{ route('fishes.show', $fish) }}" class="card">
    <div class="thumb">
        @if($fish->image)
            <img src="{{ asset('storage/' . $fish->image) }}" alt="{{ $fish->name }}">
        @else
            🐟
        @endif
    </div>

    <div class="card-body">
        <h3>{{ $fish->name }}</h3>

        @if($fish->scientific_name)
            <p><em>{{ $fish->scientific_name }}</em></p>
        @endif

        <div class="badge-row">
            <span class="badge">{{ $fish->region?->name ?? '-' }}</span>
            <span class="badge">{{ $fish->category?->name ?? '-' }}</span>
        </div>

        <p>{{ \Illuminate\Support\Str::limit(strip_tags($fish->description), 110) }}</p>
    </div>
</a>