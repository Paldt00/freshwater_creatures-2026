<?php
namespace App\Filament\Admin\Resources\WebSettingResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Admin\Resources\WebSettingResource;
use Illuminate\Routing\Router;


class WebSettingApiService extends ApiService
{
    protected static string | null $resource = WebSettingResource::class;

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
