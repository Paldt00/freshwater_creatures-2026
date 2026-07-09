<?php

namespace App\Filament\Admin\Resources\CreatureRequestResource\Pages;

use App\Filament\Admin\Resources\CreatureRequestResource;
use App\Models\CreatureRequest;
use App\Models\Fish;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class EditCreatureRequest extends EditRecord
{
    protected static string $resource =
        CreatureRequestResource::class;

    public function getTitle(): string
    {
        return 'Periksa Pengajuan: '
            . $this->getCreatureRequest()->name;
    }

    public function getBreadcrumb(): string
    {
        return 'Periksa';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(
                    'Informasi Pengajuan'
                )
                    ->description(
                        'Informasi pengguna dan status pengajuan.'
                    )
                    ->schema([
                        Forms\Components\Placeholder::make(
                            'user_display'
                        )
                            ->label('Pengguna')
                            ->content(
                                fn (): string =>
                                    $this
                                        ->getCreatureRequest()
                                        ->user
                                        ?->name
                                    ?? '-'
                            ),

                        Forms\Components\Placeholder::make(
                            'request_type_display'
                        )
                            ->label('Jenis Pengajuan')
                            ->content(
                                fn (): string =>
                                    $this
                                        ->getCreatureRequest()
                                        ->request_type_label
                            ),

                        Forms\Components\Placeholder::make(
                            'request_status_display'
                        )
                            ->label('Status Pengajuan')
                            ->content(
                                fn (): HtmlString =>
                                    $this->renderStatus()
                            ),

                        Forms\Components\Placeholder::make(
                            'created_at_display'
                        )
                            ->label('Tanggal Pengajuan')
                            ->content(
                                fn (): string =>
                                    $this
                                        ->getCreatureRequest()
                                        ->created_at
                                        ?->format(
                                            'd M Y, H:i'
                                        )
                                    ?? '-'
                            ),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(
                    'Perbandingan Data'
                )
                    ->description(
                        'Kolom berwarna menandakan data yang berbeda dari data ikan sebelumnya.'
                    )
                    ->schema([
                        ...$this->comparisonRow(
                            'name',
                            'Nama Ikan'
                        ),

                        ...$this->comparisonRow(
                            'scientific_name',
                            'Nama Ilmiah'
                        ),

                        ...$this->comparisonRow(
                            'region',
                            'Wilayah'
                        ),

                        ...$this->comparisonRow(
                            'category',
                            'Kategori'
                        ),

                        ...$this->comparisonRow(
                            'habitat',
                            'Habitat'
                        ),

                        ...$this->comparisonRow(
                            'food',
                            'Makanan'
                        ),

                        ...$this->comparisonRow(
                            'conservation_status',
                            'Status Kelestarian'
                        ),

                        ...$this->comparisonRow(
                            'biogeography',
                            'Biogeografi'
                        ),

                        ...$this->comparisonRow(
                            'characteristics',
                            'Ciri-ciri'
                        ),

                        ...$this->comparisonRow(
                            'description',
                            'Deskripsi'
                        ),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(
                    'Perbandingan Gambar'
                )
                    ->schema([
                        Forms\Components\Placeholder::make(
                            'old_image_display'
                        )
                            ->label('Gambar Lama')
                            ->content(
                                fn (): HtmlString =>
                                    $this->renderImage(
                                        'old'
                                    )
                            ),

                        Forms\Components\Placeholder::make(
                            'new_image_display'
                        )
                            ->label('Gambar Diajukan')
                            ->content(
                                fn (): HtmlString =>
                                    $this->renderImage(
                                        'new'
                                    )
                            ),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(
                    'Catatan'
                )
                    ->schema([
                        Forms\Components\Placeholder::make(
                            'request_note_display'
                        )
                            ->label('Catatan Pengguna')
                            ->content(
                                fn (): HtmlString =>
                                    $this->renderPlainBox(
                                        $this
                                            ->getCreatureRequest()
                                            ->request_note
                                        ?: 'Tidak ada catatan pengguna.'
                                    )
                            ),

                        Forms\Components\Placeholder::make(
                            'admin_note_display'
                        )
                            ->label(
                                'Catatan Administrator'
                            )
                            ->content(
                                fn (): HtmlString =>
                                    $this->renderPlainBox(
                                        $this
                                            ->getCreatureRequest()
                                            ->admin_note
                                        ?: 'Belum ada catatan administrator.'
                                    )
                            ),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(
                    'Informasi Pemeriksaan'
                )
                    ->schema([
                        Forms\Components\Placeholder::make(
                            'reviewer_display'
                        )
                            ->label('Diperiksa Oleh')
                            ->content(
                                fn (): string =>
                                    $this
                                        ->getCreatureRequest()
                                        ->reviewer
                                        ?->name
                                    ?? 'Belum diperiksa'
                            ),

                        Forms\Components\Placeholder::make(
                            'reviewed_at_display'
                        )
                            ->label('Waktu Pemeriksaan')
                            ->content(
                                fn (): string =>
                                    $this
                                        ->getCreatureRequest()
                                        ->reviewed_at
                                        ?->format(
                                            'd M Y, H:i'
                                        )
                                    ?? 'Belum diperiksa'
                            ),
                    ])
                    ->columns(2)
                    ->collapsed(
                        fn (): bool =>
                            $this
                                ->getCreatureRequest()
                                ->request_status
                            === 'pending'
                    ),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(
                    CreatureRequestResource::getUrl(
                        'index'
                    )
                ),

            Actions\Action::make('approve')
                ->label('Setujui')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(
                    fn (): bool =>
                        $this
                            ->getCreatureRequest()
                            ->request_status
                        === 'pending'
                )
                ->requiresConfirmation()
                ->modalHeading(
                    'Setujui Pengajuan'
                )
                ->modalDescription(
                    'Data yang diajukan akan diterapkan ke data ikan dan dipublikasikan.'
                )
                ->modalSubmitActionLabel(
                    'Setujui'
                )
                ->modalCancelActionLabel(
                    'Batal'
                )
                ->form([
                    Forms\Components\Textarea::make(
                        'admin_note'
                    )
                        ->label(
                            'Catatan Administrator'
                        )
                        ->placeholder(
                            'Catatan persetujuan bersifat opsional.'
                        )
                        ->rows(4),
                ])
                ->action(
                    function (array $data): void {
                        $this->approveRequest(
                            $data
                        );
                    }
                ),

            Actions\Action::make('reject')
                ->label('Tolak')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(
                    fn (): bool =>
                        $this
                            ->getCreatureRequest()
                            ->request_status
                        === 'pending'
                )
                ->requiresConfirmation()
                ->modalHeading(
                    'Tolak Pengajuan'
                )
                ->modalDescription(
                    'Alasan penolakan akan dapat dilihat oleh pengguna.'
                )
                ->modalSubmitActionLabel(
                    'Tolak'
                )
                ->modalCancelActionLabel(
                    'Batal'
                )
                ->form([
                    Forms\Components\Textarea::make(
                        'admin_note'
                    )
                        ->label(
                            'Alasan Penolakan'
                        )
                        ->placeholder(
                            'Jelaskan alasan pengajuan ditolak.'
                        )
                        ->required()
                        ->rows(4),
                ])
                ->action(
                    function (array $data): void {
                        $this->rejectRequest(
                            $data
                        );
                    }
                ),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return CreatureRequestResource::getUrl(
            'index'
        );
    }

    private function approveRequest(
        array $data
    ): void {
        $creatureRequest =
            $this->getCreatureRequest();

        $creatureRequest->refresh();

        $creatureRequest->load([
            'fish',
        ]);

        if (
            $creatureRequest->request_status
            !== 'pending'
        ) {
            Notification::make()
                ->title(
                    'Pengajuan sudah diproses.'
                )
                ->warning()
                ->send();

            return;
        }

        if (
            $creatureRequest->request_type
            === 'update'
            && ! $creatureRequest->fish
        ) {
            Notification::make()
                ->title(
                    'Data ikan lama tidak ditemukan.'
                )
                ->body(
                    'Pengajuan tidak dapat disetujui karena data ikan yang ingin diubah sudah tidak tersedia.'
                )
                ->danger()
                ->send();

            return;
        }

        $reviewerId = Auth::guard(
            'admin'
        )->id();

        if ($reviewerId === null) {
            Notification::make()
                ->title(
                    'Sesi administrator tidak ditemukan.'
                )
                ->body(
                    'Silakan masuk kembali ke panel administrator.'
                )
                ->danger()
                ->send();

            return;
        }

        DB::transaction(
            function () use (
                $creatureRequest,
                $data,
                $reviewerId
            ): void {
                if (
                    $creatureRequest
                        ->request_type
                    === 'add'
                ) {
                    Fish::query()->create([
                        'region_id' =>
                            $creatureRequest
                                ->region_id,

                        'category_id' =>
                            $creatureRequest
                                ->category_id,

                        'name' =>
                            $creatureRequest
                                ->name,

                        'scientific_name' =>
                            $creatureRequest
                                ->scientific_name,

                        'image' =>
                            $creatureRequest
                                ->image,

                        'habitat' =>
                            $creatureRequest
                                ->habitat,

                        'food' =>
                            $creatureRequest
                                ->food,

                        'conservation_status' =>
                            $creatureRequest
                                ->conservation_status,

                        'biogeography' =>
                            $creatureRequest
                                ->biogeography,

                        'characteristics' =>
                            $creatureRequest
                                ->characteristics,

                        'description' =>
                            $creatureRequest
                                ->description,

                        'is_featured' =>
                            false,

                        'is_published' =>
                            true,
                    ]);
                }

                if (
                    $creatureRequest
                        ->request_type
                    === 'update'
                ) {
                    $updateData = [
                        'region_id' =>
                            $creatureRequest
                                ->region_id,

                        'category_id' =>
                            $creatureRequest
                                ->category_id,

                        'name' =>
                            $creatureRequest
                                ->name,

                        'scientific_name' =>
                            $creatureRequest
                                ->scientific_name,

                        'habitat' =>
                            $creatureRequest
                                ->habitat,

                        'food' =>
                            $creatureRequest
                                ->food,

                        'conservation_status' =>
                            $creatureRequest
                                ->conservation_status,

                        'biogeography' =>
                            $creatureRequest
                                ->biogeography,

                        'characteristics' =>
                            $creatureRequest
                                ->characteristics,

                        'description' =>
                            $creatureRequest
                                ->description,
                    ];

                    if (
                        $creatureRequest->image
                    ) {
                        $updateData['image'] =
                            $creatureRequest
                                ->image;
                    }

                    $creatureRequest
                        ->fish
                        ?->update(
                            $updateData
                        );
                }

                $creatureRequest->update([
                    'request_status' =>
                        'approved',

                    'admin_note' =>
                        $data['admin_note']
                        ?? $creatureRequest
                            ->admin_note,

                    'reviewed_by' =>
                        $reviewerId,

                    'reviewed_at' =>
                        now(),
                ]);
            }
        );

        Notification::make()
            ->title(
                'Pengajuan berhasil disetujui.'
            )
            ->body(
                'Data ikan telah diperbarui dan dipublikasikan.'
            )
            ->success()
            ->send();

        $this->redirect(
            CreatureRequestResource::getUrl(
                'index'
            )
        );
    }

    private function rejectRequest(
        array $data
    ): void {
        $creatureRequest =
            $this->getCreatureRequest();

        $creatureRequest->refresh();

        if (
            $creatureRequest->request_status
            !== 'pending'
        ) {
            Notification::make()
                ->title(
                    'Pengajuan sudah diproses.'
                )
                ->warning()
                ->send();

            return;
        }

        $reviewerId = Auth::guard(
            'admin'
        )->id();

        if ($reviewerId === null) {
            Notification::make()
                ->title(
                    'Sesi administrator tidak ditemukan.'
                )
                ->body(
                    'Silakan masuk kembali ke panel administrator.'
                )
                ->danger()
                ->send();

            return;
        }

        $creatureRequest->update([
            'request_status' =>
                'rejected',

            'admin_note' =>
                $data['admin_note'],

            'reviewed_by' =>
                $reviewerId,

            'reviewed_at' =>
                now(),
        ]);

        Notification::make()
            ->title(
                'Pengajuan berhasil ditolak.'
            )
            ->body(
                'Alasan penolakan telah disimpan.'
            )
            ->danger()
            ->send();

        $this->redirect(
            CreatureRequestResource::getUrl(
                'index'
            )
        );
    }

    private function comparisonRow(
        string $field,
        string $label
    ): array {
        return [
            Forms\Components\Placeholder::make(
                "old_{$field}"
            )
                ->label(
                    "{$label} Lama"
                )
                ->content(
                    fn (): HtmlString =>
                        $this
                            ->renderComparisonText(
                                $field,
                                'old'
                            )
                ),

            Forms\Components\Placeholder::make(
                "new_{$field}"
            )
                ->label(
                    "{$label} Diajukan"
                )
                ->content(
                    fn (): HtmlString =>
                        $this
                            ->renderComparisonText(
                                $field,
                                'new'
                            )
                ),
        ];
    }

    private function renderComparisonText(
        string $field,
        string $side
    ): HtmlString {
        $value = $side === 'old'
            ? $this->getOldDisplayValue(
                $field
            )
            : $this->getNewDisplayValue(
                $field
            );

        $changed = $side === 'new'
            && $this->fieldHasChanged(
                $field
            );

        $background = $changed
            ? '#fff7ed'
            : '#f8fafc';

        $border = $changed
            ? '#fb923c'
            : '#e5e7eb';

        $badge = $changed
            ? '
                <span style="
                    display:inline-flex;
                    margin-bottom:8px;
                    padding:3px 8px;
                    border-radius:999px;
                    background:#ffedd5;
                    color:#c2410c;
                    font-size:12px;
                    font-weight:700;
                ">
                    Berubah
                </span>
            '
            : '';

        $cleanValue = $this->cleanText(
            $value
        );

        return new HtmlString(
            '<div style="
                min-height:72px;
                padding:14px;
                border:1px solid '
            . $border
            . ';
                border-radius:12px;
                background:'
            . $background
            . ';
                white-space:pre-wrap;
                word-break:break-word;
            ">'
            . $badge
            . '<div>'
            . nl2br(
                e($cleanValue)
            )
            . '</div>'
            . '</div>'
        );
    }

    private function renderImage(
        string $side
    ): HtmlString {
        $creatureRequest =
            $this->getCreatureRequest();

        $oldImage =
            $creatureRequest
                ->fish
                ?->image;

        $newImage =
            $creatureRequest
                ->image;

        $changed = false;
        $message = null;
        $imagePath = null;

        if ($side === 'old') {
            $imagePath = $oldImage;

            if (
                $creatureRequest
                    ->request_type
                === 'add'
            ) {
                $message =
                    'Pengajuan penambahan data baru tidak memiliki gambar lama.';
            }
        } else {
            if ($newImage) {
                $imagePath = $newImage;
                $changed = true;
            } elseif (
                $creatureRequest
                    ->request_type
                === 'update'
            ) {
                $imagePath = $oldImage;

                $message =
                    'Tidak ada gambar baru. Gambar lama tetap digunakan.';
            } else {
                $message =
                    'Tidak ada gambar yang diajukan.';
            }
        }

        $background = $changed
            ? '#fff7ed'
            : '#f8fafc';

        $border = $changed
            ? '#fb923c'
            : '#e5e7eb';

        $badge = $changed
            ? '
                <span style="
                    display:inline-flex;
                    margin-bottom:10px;
                    padding:3px 8px;
                    border-radius:999px;
                    background:#ffedd5;
                    color:#c2410c;
                    font-size:12px;
                    font-weight:700;
                ">
                    Gambar Baru
                </span>
            '
            : '';

        if (! $imagePath) {
            return new HtmlString(
                '<div style="
                    min-height:180px;
                    padding:20px;
                    border:1px solid '
                . $border
                . ';
                    border-radius:12px;
                    background:'
                . $background
                . ';
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    text-align:center;
                    color:#6b7280;
                ">'
                . e(
                    $message
                    ?? 'Gambar tidak tersedia.'
                )
                . '</div>'
            );
        }

        $url = asset(
            'storage/'
            . ltrim(
                (string) $imagePath,
                '/'
            )
        );

        return new HtmlString(
            '<div style="
                padding:14px;
                border:1px solid '
            . $border
            . ';
                border-radius:12px;
                background:'
            . $background
            . ';
            ">'
            . $badge
            . '<img
                src="'
            . e($url)
            . '"
                alt="Gambar ikan"
                style="
                    width:100%;
                    max-height:300px;
                    object-fit:contain;
                    border-radius:10px;
                    background:white;
                "
            >'
            . (
                $message
                ? '<p style="
                    margin:10px 0 0;
                    color:#6b7280;
                    font-size:13px;
                ">'
                    . e($message)
                    . '</p>'
                : ''
            )
            . '</div>'
        );
    }

    private function renderStatus(): HtmlString
    {
        $creatureRequest =
            $this->getCreatureRequest();

        [$background, $color] = match (
            $creatureRequest
                ->request_status
        ) {
            'approved' => [
                '#ecfdf5',
                '#15803d',
            ],

            'rejected' => [
                '#fef2f2',
                '#dc2626',
            ],

            default => [
                '#fff7ed',
                '#d97706',
            ],
        };

        return new HtmlString(
            '<span style="
                display:inline-flex;
                padding:5px 11px;
                border-radius:999px;
                background:'
            . $background
            . ';
                color:'
            . $color
            . ';
                font-weight:700;
            ">'
            . e(
                $creatureRequest
                    ->request_status_label
            )
            . '</span>'
        );
    }

    private function renderPlainBox(
        ?string $value
    ): HtmlString {
        return new HtmlString(
            '<div style="
                min-height:80px;
                padding:14px;
                border:1px solid #e5e7eb;
                border-radius:12px;
                background:#f8fafc;
                white-space:pre-wrap;
                word-break:break-word;
            ">'
            . nl2br(
                e(
                    $this->cleanText(
                        $value
                    )
                )
            )
            . '</div>'
        );
    }

    private function getOldDisplayValue(
        string $field
    ): mixed {
        $creatureRequest =
            $this->getCreatureRequest();

        $fish =
            $creatureRequest->fish;

        if (! $fish) {
            return 'Tidak ada data lama';
        }

        return match ($field) {
            'region' =>
                $fish->region?->name,

            'category' =>
                $fish->category?->name,

            'conservation_status' =>
                Fish::CONSERVATION_STATUSES[
                    $fish->conservation_status
                ]
                ?? 'Belum Ditentukan',

            'biogeography' =>
                Fish::BIOGEOGRAPHY_TYPES[
                    $fish->biogeography
                ]
                ?? 'Belum Ditentukan',

            default =>
                $fish->{$field},
        };
    }

    private function getNewDisplayValue(
        string $field
    ): mixed {
        $creatureRequest =
            $this->getCreatureRequest();

        return match ($field) {
            'region' =>
                $creatureRequest
                    ->region
                    ?->name,

            'category' =>
                $creatureRequest
                    ->category
                    ?->name,

            'conservation_status' =>
                Fish::CONSERVATION_STATUSES[
                    $creatureRequest
                        ->conservation_status
                ]
                ?? 'Belum Ditentukan',

            'biogeography' =>
                Fish::BIOGEOGRAPHY_TYPES[
                    $creatureRequest
                        ->biogeography
                ]
                ?? 'Belum Ditentukan',

            default =>
                $creatureRequest->{$field},
        };
    }

    private function getOldRawValue(
        string $field
    ): mixed {
        $creatureRequest =
            $this->getCreatureRequest();

        $fish =
            $creatureRequest->fish;

        if (! $fish) {
            return null;
        }

        return match ($field) {
            'region' =>
                $fish->region_id,

            'category' =>
                $fish->category_id,

            default =>
                $fish->{$field},
        };
    }

    private function getNewRawValue(
        string $field
    ): mixed {
        $creatureRequest =
            $this->getCreatureRequest();

        return match ($field) {
            'region' =>
                $creatureRequest
                    ->region_id,

            'category' =>
                $creatureRequest
                    ->category_id,

            default =>
                $creatureRequest->{$field},
        };
    }

    private function fieldHasChanged(
        string $field
    ): bool {
        $creatureRequest =
            $this->getCreatureRequest();

        if (
            $creatureRequest
                ->request_type
            === 'add'
        ) {
            return true;
        }

        return $this->normalizeValue(
            $this->getOldRawValue(
                $field
            )
        ) !== $this->normalizeValue(
            $this->getNewRawValue(
                $field
            )
        );
    }

    private function normalizeValue(
        mixed $value
    ): string {
        $value = html_entity_decode(
            strip_tags(
                (string) $value
            )
        );

        $value = preg_replace(
            '/\s+/',
            ' ',
            $value
        );

        return trim(
            mb_strtolower(
                (string) $value
            )
        );
    }

    private function cleanText(
        mixed $value
    ): string {
        $value = html_entity_decode(
            strip_tags(
                (string) $value
            )
        );

        $value = trim($value);

        return $value !== ''
            ? $value
            : '-';
    }

    private function getCreatureRequest():
        CreatureRequest
    {
        /** @var CreatureRequest $record */
        $record =
            $this->getRecord();

        $record->loadMissing([
            'user',
            'fish.region',
            'fish.category',
            'region',
            'category',
            'reviewer',
        ]);

        return $record;
    }
}
