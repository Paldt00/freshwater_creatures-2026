<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FishController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/wilayah', [RegionController::class, 'index'])->name('regions.index');
Route::get('/wilayah/{region:slug}', [RegionController::class, 'show'])->name('regions.show');

Route::get('/kategori', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/kategori/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/ikan', [FishController::class, 'index'])->name('fishes.index');
Route::get('/ikan/{fish:slug}', [FishController::class, 'show'])->name('fishes.show');

Route::get('/search', [SearchController::class, 'index'])->name('search');

// Alias sesuai istilah teknis BRD
Route::get('/region/{region:slug}', [RegionController::class, 'show'])->name('regions.show.legacy');
Route::get('/fish/{fish:slug}', [FishController::class, 'show'])->name('fishes.show.legacy');
