<?php
namespace App\Filament\Admin\Resources\RegionResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Admin\Resources\RegionResource;
use App\Filament\Admin\Resources\RegionResource\Api\Requests\CreateRegionRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = RegionResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Region
     *
     * @param CreateRegionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateRegionRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}