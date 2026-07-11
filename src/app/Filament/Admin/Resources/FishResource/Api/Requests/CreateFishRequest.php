<?php

namespace App\Filament\Admin\Resources\FishResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFishRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'region_id' => 'required',
			'category_id' => 'required',
			'name' => 'required',
			'slug' => 'required',
			'scientific_name' => 'required',
			'image' => 'required',
			'habitat' => 'required',
			'food' => 'required',
			'conservation_status' => 'required',
			'biogeography' => 'required',
			'characteristics' => 'required',
			'description' => 'required',
			'is_featured' => 'required',
			'is_published' => 'required'
		];
    }
}
