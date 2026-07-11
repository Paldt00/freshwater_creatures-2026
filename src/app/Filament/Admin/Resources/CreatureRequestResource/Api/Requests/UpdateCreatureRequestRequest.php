<?php

namespace App\Filament\Admin\Resources\CreatureRequestResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCreatureRequestRequest extends FormRequest
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
			'user_id' => 'required',
			'fish_id' => 'required',
			'region_id' => 'required',
			'category_id' => 'required',
			'request_type' => 'required',
			'request_status' => 'required',
			'name' => 'required',
			'scientific_name' => 'required',
			'image' => 'required',
			'habitat' => 'required',
			'food' => 'required',
			'conservation_status' => 'required',
			'biogeography' => 'required',
			'characteristics' => 'required',
			'description' => 'required',
			'request_note' => 'required|string',
			'admin_note' => 'required|string',
			'reviewed_by' => 'required',
			'reviewed_at' => 'required'
		];
    }
}
