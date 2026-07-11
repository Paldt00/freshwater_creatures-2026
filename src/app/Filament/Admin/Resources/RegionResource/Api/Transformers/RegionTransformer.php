<?php
namespace App\Filament\Admin\Resources\RegionResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Region;

/**
 * @property Region $resource
 */
class RegionTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->toArray();
    }
}
