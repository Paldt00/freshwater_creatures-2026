<?php
namespace App\Filament\Admin\Resources\CreatureRequestResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Admin\Resources\CreatureRequestResource;
use App\Filament\Admin\Resources\CreatureRequestResource\Api\Requests\CreateCreatureRequestRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = CreatureRequestResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create CreatureRequest
     *
     * @param CreateCreatureRequestRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateCreatureRequestRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}