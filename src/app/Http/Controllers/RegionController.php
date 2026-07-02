<?php

namespace App\Http\Controllers;

use App\Models\Region;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::query()
            ->withCount(['fishes' => fn ($query) => $query->published()])
            ->orderBy('name')
            ->paginate(12);

        return view('regions.index', compact('regions'));
    }

    public function show(Region $region)
    {
        $region->loadCount(['fishes' => fn ($query) => $query->published()]);

        $fishes = $region->fishes()
            ->with(['category', 'region'])
            ->published()
            ->latest()
            ->paginate(12);

        return view('regions.show', compact('region', 'fishes'));
    }
}
