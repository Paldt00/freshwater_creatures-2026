<?php

namespace App\Http\Controllers;

use App\Models\Fish;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->query('q'));

        $fishes = Fish::query()
            ->with(['region', 'category'])
            ->published()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery
                        ->where('name', 'like', "%{$keyword}%")
                        ->orWhere('scientific_name', 'like', "%{$keyword}%")
                        ->orWhere('habitat', 'like', "%{$keyword}%")
                        ->orWhere('food', 'like', "%{$keyword}%")
                        ->orWhere('characteristics', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%")
                        ->orWhereHas('region', function ($regionQuery) use ($keyword) {
                            $regionQuery->where('name', 'like', "%{$keyword}%");
                        })
                        ->orWhereHas('category', function ($categoryQuery) use ($keyword) {
                            $categoryQuery->where('name', 'like', "%{$keyword}%");
                        });
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('search.index', compact('fishes', 'keyword'));
    }
}
