<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Fish extends Model
{
    protected $table = 'fishes';

    public const CONSERVATION_STATUSES = [
        'extinct' => 'Extinct (Punah)',
        'endangered' => 'Endangered (Terancam Punah)',
        'least_concern' => 'Least Concern (Risiko Rendah)',
    ];

    public const BIOGEOGRAPHY_TYPES = [
        'native' => 'Native (Spesies Asli)',
        'endemic' => 'Endemic (Spesies Endemik)',
        'introduction' => 'Introduction (Spesies Pendatang)',
    ];

    protected $fillable = [
        'region_id',
        'category_id',
        'name',
        'slug',
        'scientific_name',
        'image',
        'habitat',
        'food',
        'conservation_status',
        'biogeography',
        'characteristics',
        'description',
        'is_featured',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Fish $fish) {
            $fish->slug = static::makeUniqueSlug(
                value: $fish->slug ?: $fish->name,
                ignoreId: $fish->id
            );
        });
    }

    public static function makeUniqueSlug(
        string $value,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($value);

        if ($baseSlug === '') {
            $baseSlug = 'ikan';
        }

        $slug = $baseSlug;
        $counter = 2;

        while (
            static::query()
                ->where('slug', $slug)
                ->when(
                    $ignoreId,
                    fn (Builder $query) => $query->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function getConservationStatusLabelAttribute(): string
    {
        return self::CONSERVATION_STATUSES[$this->conservation_status]
            ?? 'Belum Ditentukan';
    }

    public function getBiogeographyLabelAttribute(): string
    {
        return self::BIOGEOGRAPHY_TYPES[$this->biogeography]
            ?? 'Belum Ditentukan';
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
