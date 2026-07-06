<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CreatureRequestController;
use App\Http\Controllers\FishController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicAuthController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Publik
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [HomeController::class, 'index']
)->name('home');

/*
|--------------------------------------------------------------------------
| Wilayah
|--------------------------------------------------------------------------
*/

Route::get(
    '/wilayah',
    [RegionController::class, 'index']
)->name('regions.index');

Route::get(
    '/wilayah/{region:slug}',
    [RegionController::class, 'show']
)->name('regions.show');

/*
|--------------------------------------------------------------------------
| Kategori
|--------------------------------------------------------------------------
*/

Route::get(
    '/kategori',
    [CategoryController::class, 'index']
)->name('categories.index');

Route::get(
    '/kategori/{category:slug}',
    [CategoryController::class, 'show']
)->name('categories.show');

/*
|--------------------------------------------------------------------------
| Ikan
|--------------------------------------------------------------------------
*/

Route::get(
    '/ikan',
    [FishController::class, 'index']
)->name('fishes.index');

Route::get(
    '/ikan/{fish:slug}',
    [FishController::class, 'show']
)->name('fishes.show');

/*
|--------------------------------------------------------------------------
| Pencarian
|--------------------------------------------------------------------------
*/

Route::get(
    '/search',
    [SearchController::class, 'index']
)->name('search');

/*
|--------------------------------------------------------------------------
| Autentikasi Publik
|--------------------------------------------------------------------------
*/

Route::get(
    '/login',
    [PublicAuthController::class, 'showLogin']
)->name('public.login.form');

Route::post(
    '/login',
    [PublicAuthController::class, 'login']
)->name('public.login');

Route::get(
    '/register',
    [PublicAuthController::class, 'showRegister']
)->name('public.register.form');

Route::post(
    '/register',
    [PublicAuthController::class, 'register']
)->name('public.register');

/*
|--------------------------------------------------------------------------
| Route yang Wajib Login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function (): void {
    Route::post(
        '/logout',
        [PublicAuthController::class, 'logout']
    )->name('public.logout');

    Route::get(
        '/requests',
        [CreatureRequestController::class, 'index']
    )->name('creature-requests.index');

    Route::get(
        '/request/create',
        [CreatureRequestController::class, 'create']
    )->name('creature-requests.create');

    Route::get(
        '/request/fish-data/{fish:id}',
        [CreatureRequestController::class, 'fishData']
    )->name('creature-requests.fish-data');

    Route::post(
        '/request',
        [CreatureRequestController::class, 'store']
    )->name('creature-requests.store');
});

/*
|--------------------------------------------------------------------------
| URL Lama
|--------------------------------------------------------------------------
| Dipertahankan agar tautan lama tetap dapat digunakan.
*/

Route::get(
    '/region/{region:slug}',
    [RegionController::class, 'show']
)->name('regions.show.legacy');

Route::get(
    '/fish/{fish:slug}',
    [FishController::class, 'show']
)->name('fishes.show.legacy');