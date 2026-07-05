<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreatureRequest extends Model
{
    public const REQUEST_TYPES = [
        'add' => 'Tambah Data',
        'update' => 'Perubahan Data',
    ];

    public const REQUEST_STATUSES = [
        'pending' => 'Menunggu Pemeriksaan',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
    ];

    protected $fillable = [
        'user_id',
        'fish_id',
        'region_id',
        'category_id',
        'request_type',
        'request_status',
        'name',
        'scientific_name',
        'image',
        'habitat',
        'food',
        'conservation_status',
        'biogeography',
        'characteristics',
        'description',
        'request_note',
        'admin_note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function getRequestTypeLabelAttribute(): string
    {
        return self::REQUEST_TYPES[$this->request_type]
            ?? 'Tidak Diketahui';
    }

    public function getRequestStatusLabelAttribute(): string
    {
        return self::REQUEST_STATUSES[$this->request_status]
            ?? 'Tidak Diketahui';
    }

    public function getConservationStatusLabelAttribute(): string
    {
        return Fish::CONSERVATION_STATUSES[$this->conservation_status]
            ?? 'Belum Ditentukan';
    }

    public function getBiogeographyLabelAttribute(): string
    {
        return Fish::BIOGEOGRAPHY_TYPES[$this->biogeography]
            ?? 'Belum Ditentukan';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fish(): BelongsTo
    {
        return $this->belongsTo(Fish::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
