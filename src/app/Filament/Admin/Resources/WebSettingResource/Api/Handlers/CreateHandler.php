<?php
namespace App\Filament\Admin\Resources\WebSettingResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Admin\Resources\WebSettingResource;
use App\Filament\Admin\Resources\WebSettingResource\Api\Requests\CreateWebSettingRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = WebSettingResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create WebSetting
     *
     * @param CreateWebSettingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateWebSettingRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}