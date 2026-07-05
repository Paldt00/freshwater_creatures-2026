<?php

namespace App\Http\Controllers;

use App\Models\Fish;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim(
            (string) $request->query('q', '')
        );

        $normalizedKeyword = Str::lower($keyword);

        $statusSearch = match ($normalizedKeyword) {
            'punah',
            'extinct' => 'extinct',

            'terancam',
            'terancam punah',
            'endangered' => 'endangered',

            'risiko rendah',
            'resiko rendah',
            'least concern',
            'least_concern' => 'least_concern',

            default => null,
        };

        $biogeographySearch = match ($normalizedKeyword) {
            'native',
            'spesies asli',
            'ikan asli',
            'asli' => 'native',

            'endemic',
            'endemik',
            'spesies endemik',
            'ikan endemik' => 'endemic',

            'introduction',
            'introduksi',
            'pendatang',
            'spesies pendatang',
            'ikan pendatang' => 'introduction',

            default => null,
        };

        $fishes = Fish::query()
            ->with([
                'region',
                'category',
            ])
            ->published()
            ->when(
                $keyword !== '',
                function ($query) use (
                    $keyword,
                    $statusSearch,
                    $biogeographySearch
                ) {
                    $query->where(
                        function ($subQuery) use (
                            $keyword,
                            $statusSearch,
                            $biogeographySearch
                        ) {
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
                                    function ($regionQuery) use ($keyword) {
                                        $regionQuery->where(
                                            'name',
                                            'like',
                                            "%{$keyword}%"
                                        );
                                    }
                                )
                                ->orWhereHas(
                                    'category',
                                    function ($categoryQuery) use ($keyword) {
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

                            if ($biogeographySearch !== null) {
                                $subQuery->orWhere(
                                    'biogeography',
                                    $biogeographySearch
                                );
                            }
                        }
                    );
                }
            )
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
}
