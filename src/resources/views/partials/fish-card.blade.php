@php
    $conservationStyle = match($fish->conservation_status) {
        'extinct' => 'background:#f3f4f6;color:#374151;',
        'endangered' => 'background:#fef2f2;color:#dc2626;',
        'least_concern' => 'background:#ecfdf5;color:#16a34a;',
        default => 'background:#fff7ed;color:#d97706;',
    };

    $biogeographyStyle = match($fish->biogeography) {
        'native' => 'background:#eff6ff;color:#2563eb;',
        'endemic' => 'background:#ecfdf5;color:#15803d;',
        'introduction' => 'background:#fff7ed;color:#d97706;',
        default => 'background:#f3f4f6;color:#4b5563;',
    };
@endphp

<a
    href="{{ route('fishes.show', $fish->slug) }}"
    class="card"
>
    <div class="thumb">
        @if($fish->image)
            <img
                src="{{ asset('storage/' . $fish->image) }}"
                alt="{{ $fish->name }}"
            >
        @else
            🐟
        @endif
    </div>

    <div class="card-body">
        <h3>{{ $fish->name }}</h3>

        @if($fish->scientific_name)
            <p>
                <em>{{ $fish->scientific_name }}</em>
            </p>
        @endif

        <div class="badge-row">
            <span class="badge">
                🗺️ {{ $fish->region?->name ?? '-' }}
            </span>

            <span class="badge">
                🏷️ {{ $fish->category?->name ?? '-' }}
            </span>

            <span
                class="badge"
                style="{{ $conservationStyle }}"
            >
                🛡️ {{ $fish->conservation_status_label }}
            </span>

            <span
                class="badge"
                style="{{ $biogeographyStyle }}"
            >
                🌍 {{ $fish->biogeography_label }}
            </span>
        </div>

        <p>
            {{
                \Illuminate\Support\Str::limit(
                    strip_tags($fish->description),
                    115
                )
                ?: 'Deskripsi ikan belum tersedia.'
            }}
        </p>
    </div>
</a>
