@extends('layouts.public')

@section('title', 'Daftar Ikan')

@section('content')
<style>
    .fish-filter-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 24px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 30px;
    }

    .fish-filter-grid {
        display: grid;
        grid-template-columns: 1.5fr repeat(4, minmax(0, 1fr));
        gap: 16px;
        align-items: end;
    }

    .fish-filter-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .filter-summary {
        margin-top: 18px;
        padding-top: 18px;
        border-top: 1px solid var(--border);
        color: var(--muted);
        font-weight: 700;
    }

    .active-filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }

    .active-filter {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 11px;
        border-radius: 999px;
        background: var(--cyan-soft);
        color: var(--blue);
        font-size: 13px;
        font-weight: 900;
    }

    @media (max-width: 1100px) {
        .fish-filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .fish-filter-grid {
            grid-template-columns: 1fr;
        }

        .fish-filter-actions .btn {
            width: 100%;
        }
    }
</style>

<div class="container">
    <div class="section-head">
        <div>
            <h2>Daftar Ikan Air Tawar</h2>

            <p>
                Cari dan filter ikan berdasarkan informasi yang tersedia.
            </p>
        </div>
    </div>

    <div class="fish-filter-card">
        <form
            action="{{ route('fishes.index') }}"
            method="GET"
        >
            <div class="fish-filter-grid">
                <div class="field">
                    <label for="q">
                        Kata Kunci
                    </label>

                    <input
                        type="text"
                        name="q"
                        id="q"
                        value="{{ $keyword }}"
                        placeholder="Nama ikan, habitat, atau nama ilmiah"
                    >
                </div>

                <div class="field">
                    <label for="region_id">
                        Wilayah
                    </label>

                    <select
                        name="region_id"
                        id="region_id"
                    >
                        <option value="">
                            Semua Wilayah
                        </option>

                        @foreach($regions as $region)
                            <option
                                value="{{ $region->id }}"
                                @selected($regionId === $region->id)
                            >
                                {{ $region->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="category_id">
                        Kategori
                    </label>

                    <select
                        name="category_id"
                        id="category_id"
                    >
                        <option value="">
                            Semua Kategori
                        </option>

                        @foreach($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected($categoryId === $category->id)
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="conservation_status">
                        Status Kelestarian
                    </label>

                    <select
                        name="conservation_status"
                        id="conservation_status"
                    >
                        <option value="">
                            Semua Status
                        </option>

                        @foreach(
                            $conservationStatuses
                            as $value => $label
                        )
                            <option
                                value="{{ $value }}"
                                @selected(
                                    $conservationStatus === $value
                                )
                            >
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="biogeography">
                        Biogeografi
                    </label>

                    <select
                        name="biogeography"
                        id="biogeography"
                    >
                        <option value="">
                            Semua Biogeografi
                        </option>

                        @foreach(
                            $biogeographyTypes
                            as $value => $label
                        )
                            <option
                                value="{{ $value }}"
                                @selected(
                                    $biogeography === $value
                                )
                            >
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="fish-filter-actions">
                <button
                    type="submit"
                    class="btn"
                >
                    🔎 Terapkan Filter
                </button>

                <a
                    href="{{ route('fishes.index') }}"
                    class="btn secondary"
                >
                    Atur Ulang
                </a>
            </div>
        </form>

        <div class="filter-summary">
            Ditemukan
            <strong>{{ $fishes->total() }}</strong>
            data ikan.

            @if(
                $keyword !== ''
                || $regionId > 0
                || $categoryId > 0
                || $conservationStatus !== null
                || $biogeography !== null
            )
                <div class="active-filter-row">
                    @if($keyword !== '')
                        <span class="active-filter">
                            Kata kunci: {{ $keyword }}
                        </span>
                    @endif

                    @if($regionId > 0)
                        <span class="active-filter">
                            Wilayah:
                            {{
                                $regions
                                    ->firstWhere('id', $regionId)
                                    ?->name
                                ?? '-'
                            }}
                        </span>
                    @endif

                    @if($categoryId > 0)
                        <span class="active-filter">
                            Kategori:
                            {{
                                $categories
                                    ->firstWhere('id', $categoryId)
                                    ?->name
                                ?? '-'
                            }}
                        </span>
                    @endif

                    @if($conservationStatus !== null)
                        <span class="active-filter">
                            Kelestarian:
                            {{
                                $conservationStatuses[
                                    $conservationStatus
                                ]
                                ?? '-'
                            }}
                        </span>
                    @endif

                    @if($biogeography !== null)
                        <span class="active-filter">
                            Biogeografi:
                            {{
                                $biogeographyTypes[
                                    $biogeography
                                ]
                                ?? '-'
                            }}
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-3">
        @forelse($fishes as $fish)
            @include('partials.fish-card', [
                'fish' => $fish,
            ])
        @empty
            <div
                class="empty"
                style="grid-column: 1 / -1;"
            >
                <div style="font-size: 48px; margin-bottom: 12px;">
                    🔍
                </div>

                <strong>
                    Data ikan tidak ditemukan.
                </strong>

                <p style="margin-top: 8px;">
                    Ubah kata kunci atau atur ulang filter.
                </p>

                <a
                    href="{{ route('fishes.index') }}"
                    class="btn secondary"
                    style="margin-top: 16px;"
                >
                    Tampilkan Semua Ikan
                </a>
            </div>
        @endforelse
    </div>

    @if($fishes->hasPages())
        <div class="pagination">
            {{ $fishes->links() }}
        </div>
    @endif
</div>
@endsection
