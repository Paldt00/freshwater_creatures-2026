<?php
namespace App\Filament\Admin\Resources\CreatureRequestResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Admin\Resources\CreatureRequestResource;
use Illuminate\Routing\Router;


class CreatureRequestApiService extends ApiService
{
    protected static string | null $resource = CreatureRequestResource::class;

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
