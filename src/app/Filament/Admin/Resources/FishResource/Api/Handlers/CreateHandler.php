<?php
namespace App\Filament\Admin\Resources\FishResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Admin\Resources\FishResource;
use App\Filament\Admin\Resources\FishResource\Api\Requests\CreateFishRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = FishResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Fish
     *
     * @param CreateFishRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateFishRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}