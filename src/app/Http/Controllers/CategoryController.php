<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->withCount(['fishes' => fn ($query) => $query->published()])
            ->orderBy('name')
            ->paginate(12);

        return view('categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        $category->loadCount(['fishes' => fn ($query) => $query->published()]);

        $fishes = $category->fishes()
            ->with(['category', 'region'])
            ->published()
            ->latest()
            ->paginate(12);

        return view('categories.show', compact('category', 'fishes'));
    }
}
