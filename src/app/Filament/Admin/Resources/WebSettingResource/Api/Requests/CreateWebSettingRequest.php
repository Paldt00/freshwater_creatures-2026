<?php

namespace App\Filament\Admin\Resources\WebSettingResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateWebSettingRequest extends FormRequest
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
			'site_name' => 'required',
			'hero_badge_text' => 'required',
			'logo' => 'required',
			'banner_image' => 'required',
			'hero_title' => 'required',
			'hero_subtitle' => 'required|string',
			'contact_email' => 'required',
			'contact_phone' => 'required',
			'address' => 'required|string',
			'instagram_url' => 'required',
			'youtube_url' => 'required',
			'tiktok_url' => 'required',
			'footer_text' => 'required|string'
		];
    }
}
