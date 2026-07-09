<?php

namespace App\Http\Controllers;

use App\Models\Fish;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = trim(
            (string) $request->query('q', '')
        );

        $fishesQuery = Fish::query()
            ->with([
                'region',
                'category',
            ])
            ->published();

        if ($keyword !== '') {
            $this->applyKeywordSearch(
                $fishesQuery,
                $keyword
            );
        }

        $fishes = $fishesQuery
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view(
            'search.index',
            compact(
                'fishes',
                'keyword'
            )
        );
    }

    public function suggestions(
        Request $request
    ): JsonResponse {
        $keyword = trim(
            (string) $request->query('q', '')
        );

        if (
            $keyword === ''
            || mb_strlen($keyword) < 2
        ) {
            return response()->json([
                'data' => [],
                'search_url' => null,
            ]);
        }

        $fishesQuery = Fish::query()
            ->with([
                'region',
                'category',
            ])
            ->published();

        $this->applyKeywordSearch(
            $fishesQuery,
            $keyword
        );

        $fishes = $fishesQuery
            ->orderByRaw(
                '
                    CASE
                        WHEN name LIKE ? THEN 0
                        WHEN scientific_name LIKE ? THEN 1
                        ELSE 2
                    END
                ',
                [
                    "{$keyword}%",
                    "{$keyword}%",
                ]
            )
            ->orderBy('name')
            ->limit(7)
            ->get();

        $suggestions = $fishes
            ->map(
                function (Fish $fish): array {
                    return [
                        'id' => $fish->id,

                        'name' => $fish->name,

                        'scientific_name' =>
                            $fish->scientific_name,

                        'region' =>
                            $fish->region?->name
                            ?? 'Wilayah belum ditentukan',

                        'category' =>
                            $fish->category?->name
                            ?? 'Kategori belum ditentukan',

                        'image' =>
                            $fish->image
                                ? asset(
                                    'storage/'
                                    . ltrim(
                                        $fish->image,
                                        '/'
                                    )
                                )
                                : null,

                        'url' => route(
                            'fishes.show',
                            [
                                'fish' => $fish->slug,
                            ]
                        ),
                    ];
                }
            )
            ->values();

        return response()
            ->json([
                'data' => $suggestions,

                'search_url' => route(
                    'search',
                    [
                        'q' => $keyword,
                    ]
                ),
            ])
            ->withHeaders([
                'Cache-Control' =>
                    'no-store, no-cache, must-revalidate, max-age=0',
            ]);
    }

    private function applyKeywordSearch(
        Builder $query,
        string $keyword
    ): Builder {
        $normalizedKeyword = Str::lower(
            $keyword
        );

        $statusSearch =
            $this->resolveConservationStatus(
                $normalizedKeyword
            );

        $biogeographySearch =
            $this->resolveBiogeography(
                $normalizedKeyword
            );

        return $query->where(
            function (
                Builder $subQuery
            ) use (
                $keyword,
                $statusSearch,
                $biogeographySearch
            ): void {
                $subQuery
                    ->where(
                        'name',
                        'like',
                        "%{$keyword}%"
                    )
                    ->orWhere(
                        'scientific_name',
                        'like',
                        "%{$keyword}%"
                    )
                    ->orWhere(
                        'habitat',
                        'like',
                        "%{$keyword}%"
                    )
                    ->orWhere(
                        'food',
                        'like',
                        "%{$keyword}%"
                    )
                    ->orWhere(
                        'characteristics',
                        'like',
                        "%{$keyword}%"
                    )
                    ->orWhere(
                        'description',
                        'like',
                        "%{$keyword}%"
                    )
                    ->orWhereHas(
                        'region',
                        function (
                            Builder $regionQuery
                        ) use ($keyword): void {
                            $regionQuery->where(
                                'name',
                                'like',
                                "%{$keyword}%"
                            );
                        }
                    )
                    ->orWhereHas(
                        'category',
                        function (
                            Builder $categoryQuery
                        ) use ($keyword): void {
                            $categoryQuery->where(
                                'name',
                                'like',
                                "%{$keyword}%"
                            );
                        }
                    );

                if ($statusSearch !== null) {
                    $subQuery->orWhere(
                        'conservation_status',
                        $statusSearch
                    );
                }

                if (
                    $biogeographySearch !== null
                ) {
                    $subQuery->orWhere(
                        'biogeography',
                        $biogeographySearch
                    );
                }
            }
        );
    }

    private function resolveConservationStatus(
        string $keyword
    ): ?string {
        return match ($keyword) {
            'punah',
            'extinct' =>
                'extinct',

            'terancam',
            'terancam punah',
            'endangered' =>
                'endangered',

            'risiko rendah',
            'resiko rendah',
            'least concern',
            'least_concern' =>
                'least_concern',

            default =>
                null,
        };
    }

    private function resolveBiogeography(
        string $keyword
    ): ?string {
        return match ($keyword) {
            'native',
            'spesies asli',
            'ikan asli',
            'asli' =>
                'native',

            'endemic',
            'endemik',
            'spesies endemik',
            'ikan endemik' =>
                'endemic',

            'introduction',
            'introduksi',
            'pendatang',
            'spesies pendatang',
            'ikan pendatang' =>
                'introduction',

            default =>
                null,
        };
    }
}
