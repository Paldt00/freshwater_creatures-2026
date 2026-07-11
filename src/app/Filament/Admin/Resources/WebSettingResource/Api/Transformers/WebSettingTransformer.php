<?php
namespace App\Filament\Admin\Resources\WebSettingResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\WebSetting;

/**
 * @property WebSetting $resource
 */
class WebSettingTransformer extends JsonResource
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
