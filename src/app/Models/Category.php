<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
    ];

    protected static function booted(): void
    {
        static::saving(function (Category $category) {
            $category->slug = static::makeUniqueSlug(
                value: $category->slug ?: $category->name,
                ignoreId: $category->id
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
