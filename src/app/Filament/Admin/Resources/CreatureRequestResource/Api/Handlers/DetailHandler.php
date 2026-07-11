<?php

namespace App\Filament\Admin\Resources\CreatureRequestResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Admin\Resources\CreatureRequestResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Admin\Resources\CreatureRequestResource\Api\Transformers\CreatureRequestTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = CreatureRequestResource::class;


    /**
     * Show CreatureRequest
     *
     * @param Request $request
     * @return CreatureRequestTransformer
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');
        
        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        return new CreatureRequestTransformer($query);
    }
}
