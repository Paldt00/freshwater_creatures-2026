<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Fish;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FishController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = trim((string) $request->query('q', ''));

        $regionId = $request->integer('region_id');
        $categoryId = $request->integer('category_id');

        $conservationStatus = $request->query('conservation_status');
        $biogeography = $request->query('biogeography');

        if (
            ! array_key_exists(
                $conservationStatus,
                Fish::CONSERVATION_STATUSES
            )
        ) {
            $conservationStatus = null;
        }

        if (
            ! array_key_exists(
                $biogeography,
                Fish::BIOGEOGRAPHY_TYPES
            )
        ) {
            $biogeography = null;
        }

        $fishes = Fish::query()
            ->with([
                'region',
                'category',
            ])
            ->published()
            ->when(
                $keyword !== '',
                function ($query) use ($keyword) {
                    $query->where(function ($subQuery) use ($keyword) {
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
                    });
                }
            )
            ->when(
                $regionId > 0,
                fn ($query) => $query->where(
                    'region_id',
                    $regionId
                )
            )
            ->when(
                $categoryId > 0,
                fn ($query) => $query->where(
                    'category_id',
                    $categoryId
                )
            )
            ->when(
                $conservationStatus !== null,
                fn ($query) => $query->where(
                    'conservation_status',
                    $conservationStatus
                )
            )
            ->when(
                $biogeography !== null,
                fn ($query) => $query->where(
                    'biogeography',
                    $biogeography
                )
            )
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $regions = Region::query()
            ->orderBy('name')
            ->get();

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $conservationStatuses = Fish::CONSERVATION_STATUSES;
        $biogeographyTypes = Fish::BIOGEOGRAPHY_TYPES;

        return view('fishes.index', compact(
            'fishes',
            'regions',
            'categories',
            'conservationStatuses',
            'biogeographyTypes',
            'keyword',
            'regionId',
            'categoryId',
            'conservationStatus',
            'biogeography'
        ));
    }

    public function show(Fish $fish): View
    {
        abort_unless($fish->is_published, 404);

        $fish->load([
            'region',
            'category',
        ]);

        $relatedFishes = Fish::query()
            ->with([
                'region',
                'category',
            ])
            ->published()
            ->where('id', '!=', $fish->id)
            ->where(function ($query) use ($fish) {
                $query
                    ->where(
                        'region_id',
                        $fish->region_id
                    )
                    ->orWhere(
                        'category_id',
                        $fish->category_id
                    );
            })
            ->latest('id')
            ->limit(4)
            ->get();

        return view('fishes.show', compact(
            'fish',
            'relatedFishes'
        ));
    }
}
