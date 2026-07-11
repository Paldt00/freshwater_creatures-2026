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
| Beranda
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
    '/search/suggestions',
    [SearchController::class, 'suggestions']
)
    ->middleware('throttle:search-suggestions')
    ->name('search.suggestions');

Route::get(
    '/search',
    [SearchController::class, 'index']
)->name('search');

/*
|--------------------------------------------------------------------------
| Pembaruan Token CSRF
|--------------------------------------------------------------------------
*/

Route::get(
    '/csrf-token',
    [
        PublicAuthController::class,
        'refreshCsrfToken',
    ]
)->name('csrf.refresh');

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
)
    ->middleware('throttle:public-login')
    ->name('public.login');

Route::get(
    '/register',
    [PublicAuthController::class, 'showRegister']
)->name('public.register.form');

Route::post(
    '/register',
    [PublicAuthController::class, 'register']
)
    ->middleware('throttle:public-register')
    ->name('public.register');

/*
|--------------------------------------------------------------------------
| Route Pengguna Terdaftar
|--------------------------------------------------------------------------
*/

Route::middleware('auth:web')->group(
    function (): void {
        Route::post(
            '/logout',
            [
                PublicAuthController::class,
                'logout',
            ]
        )->name('public.logout');

        Route::get(
            '/requests',
            [
                CreatureRequestController::class,
                'index',
            ]
        )->name('creature-requests.index');

        Route::get(
            '/request/create',
            [
                CreatureRequestController::class,
                'create',
            ]
        )->name('creature-requests.create');

        Route::get(
            '/request/fish-data/{fish:id}',
            [
                CreatureRequestController::class,
                'fishData',
            ]
        )->name('creature-requests.fish-data');

        Route::post(
            '/request',
            [
                CreatureRequestController::class,
                'store',
            ]
        )->name('creature-requests.store');
    }
);
