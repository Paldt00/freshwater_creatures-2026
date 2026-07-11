<?php
namespace App\Filament\Admin\Resources\FishResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Fish;

/**
 * @property Fish $resource
 */
class FishTransformer extends JsonResource
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
