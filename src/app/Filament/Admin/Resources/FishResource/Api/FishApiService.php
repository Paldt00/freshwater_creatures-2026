<?php
namespace App\Filament\Admin\Resources\FishResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Admin\Resources\FishResource;
use Illuminate\Routing\Router;


class FishApiService extends ApiService
{
    protected static string | null $resource = FishResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
