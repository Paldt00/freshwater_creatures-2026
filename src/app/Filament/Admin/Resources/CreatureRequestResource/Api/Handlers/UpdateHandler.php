<?php
namespace App\Filament\Admin\Resources\CreatureRequestResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Admin\Resources\CreatureRequestResource;
use App\Filament\Admin\Resources\CreatureRequestResource\Api\Requests\UpdateCreatureRequestRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = CreatureRequestResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update CreatureRequest
     *
     * @param UpdateCreatureRequestRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateCreatureRequestRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}