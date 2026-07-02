<?php

namespace App\Http\Controllers;

use App\Models\Fish;

class FishController extends Controller
{
    public function index()
    {
        $fishes = Fish::query()
            ->with(['region', 'category'])
            ->published()
            ->latest()
            ->paginate(12);

        return view('fishes.index', compact('fishes'));
    }

    public function show(Fish $fish)
    {
        abort_unless($fish->is_published, 404);

        $fish->load(['region', 'category']);

        $relatedFishes = Fish::query()
            ->with(['region', 'category'])
            ->published()
            ->where('id', '!=', $fish->id)
            ->where(function ($query) use ($fish) {
                $query->where('region_id', $fish->region_id)
                    ->orWhere('category_id', $fish->category_id);
            })
            ->latest()
            ->take(4)
            ->get();

        return view('fishes.show', compact('fish', 'relatedFishes'));
    }
}
