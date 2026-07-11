<?php
namespace App\Filament\Admin\Resources\CreatureRequestResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\CreatureRequest;

/**
 * @property CreatureRequest $resource
 */
class CreatureRequestTransformer extends JsonResource
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
