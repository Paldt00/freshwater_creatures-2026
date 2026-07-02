<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Region extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
    ];

    protected static function booted(): void
    {
        static::saving(function (Region $region) {
            $region->slug = static::makeUniqueSlug(
                value: $region->slug ?: $region->name,
                ignoreId: $region->id
            );
        });
    }

    public static function makeUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($value);
        $slug = $baseSlug;
        $counter = 2;

        while (
            static::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function fishes(): HasMany
    {
        return $this->hasMany(Fish::class);
    }
}
