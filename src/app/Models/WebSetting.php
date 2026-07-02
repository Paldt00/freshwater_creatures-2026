<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model
{
    protected $fillable = [
        'site_name',
        'logo',
        'banner_image',
        'hero_title',
        'hero_subtitle',
        'contact_email',
        'contact_phone',
        'address',
        'instagram_url',
        'youtube_url',
        'tiktok_url',
        'footer_text',
    ];
}
