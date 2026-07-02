<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Fish;
use App\Models\Region;
use App\Models\WebSetting;

class HomeController extends Controller
{
    public function index()
    {
        $setting = WebSetting::query()->first();

        $regions = Region::query()
            ->withCount(['fishes' => fn ($query) => $query->published()])
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::query()
            ->withCount(['fishes' => fn ($query) => $query->published()])
            ->orderBy('name')
            ->take(8)
            ->get();

        $featuredFishes = Fish::query()
            ->with(['region', 'category'])
            ->published()
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        $latestFishes = Fish::query()
            ->with(['region', 'category'])
            ->published()
            ->latest()
            ->take(6)
            ->get();

        $stats = [
            'fish_count' => Fish::published()->count(),
            'region_count' => Region::count(),
            'category_count' => Category::count(),
        ];

        return view('home', compact(
            'setting',
            'regions',
            'categories',
            'featuredFishes',
            'latestFishes',
            'stats'
        ));
    }
}
