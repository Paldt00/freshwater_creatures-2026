<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CreatureRequestResource\Pages;
use App\Models\CreatureRequest;
use App\Models\Fish;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CreatureRequestResource extends Resource
{
    protected static ?string $model = CreatureRequest::class;

    protected static ?string $navigationIcon =
        'heroicon-o-document-check';

    protected static ?string $navigationLabel =
        'Pengajuan Data';

    protected static ?string $modelLabel =
        'Pengajuan';

    protected static ?string $pluralModelLabel =
        'Pengajuan Data';

    protected static ?string $navigationGroup =
        'Manajemen Data';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        $total = CreatureRequest::query()
            ->where('request_status', 'pending')
            ->count();

        return $total > 0
            ? (string) $total
            : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Jumlah pengajuan yang menunggu pemeriksaan';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(
                    'Informasi Pengajuan'
                )
                    ->description(
                        'Informasi pengguna, jenis pengajuan, dan status pemeriksaan.'
                    )
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Pengguna')
                            ->relationship('user', 'name')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('fish_id')
                            ->label('Data Ikan Lama')
                            ->relationship('fish', 'name')
                            ->helperText(
                                'Hanya terisi pada pengajuan perubahan data.'
                            )
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('request_type')
                            ->label('Jenis Pengajuan')
                            ->options(
                                CreatureRequest::REQUEST_TYPES
                            )
                            ->disabled()
                            ->dehydrated(false)
                            ->native(false),

                        Forms\Components\Select::make('request_status')
                            ->label('Status Pengajuan')
                            ->options(
                                CreatureRequest::REQUEST_STATUSES
                            )
                            ->disabled()
                            ->dehydrated(false)
                            ->native(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(
                    'Data yang Diajukan'
                )
                    ->description(
                        'Data berikut merupakan informasi yang diajukan oleh pengguna.'
                    )
                    ->schema([
                        Forms\Components\Select::make('region_id')
                            ->label('Wilayah')
                            ->relationship('region', 'name')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Ikan')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make(
                            'scientific_name'
                        )
                            ->label('Nama Ilmiah')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar Ikan')
                            ->image()
                            ->directory('request-fishes')
                            ->visibility('public')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('habitat')
                            ->label('Habitat')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('food')
                            ->label('Makanan')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make(
                            'conservation_status'
                        )
                            ->label('Status Kelestarian')
                            ->options(
                                Fish::CONSERVATION_STATUSES
                            )
                            ->native(false)
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make(
                            'biogeography'
                        )
                            ->label('Biogeografi')
                            ->options(
                                Fish::BIOGEOGRAPHY_TYPES
                            )
                            ->native(false)
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Textarea::make(
                            'characteristics'
                        )
                            ->label('Ciri-ciri')
                            ->rows(4)
                            ->columnSpanFull()
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Textarea::make(
                            'description'
                        )
                            ->label('Deskripsi')
                            ->rows(5)
                            ->columnSpanFull()
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Catatan')
                    ->schema([
                        Forms\Components\Textarea::make(
                            'request_note'
                        )
                            ->label('Catatan Pengguna')
                            ->rows(4)
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Textarea::make(
                            'admin_note'
                        )
                            ->label('Catatan Administrator')
                            ->rows(4)
                            ->placeholder(
                                'Tambahkan catatan hasil pemeriksaan.'
                            ),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(
                    'Informasi Pemeriksaan'
                )
                    ->schema([
                        Forms\Components\Select::make(
                            'reviewed_by'
                        )
                            ->label('Diperiksa Oleh')
                            ->relationship('reviewer', 'name')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\DateTimePicker::make(
                            'reviewed_at'
                        )
                            ->label('Waktu Pemeriksaan')
                            ->displayFormat('d M Y, H:i')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Ikan')
                    ->description(
                        fn (
                            CreatureRequest $record
                        ): ?string => $record->scientific_name
                    )
                    ->searchable([
                        'name',
                        'scientific_name',
                    ])
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make(
                    'request_type'
                )
                    ->label('Jenis Pengajuan')
                    ->badge()
                    ->formatStateUsing(
                        fn (?string $state): string =>
                            CreatureRequest::REQUEST_TYPES[
                                $state
                            ]
                            ?? 'Tidak Diketahui'
                    )
                    ->color(
                        fn (?string $state): string => match (
                            $state
                        ) {
                            'add' => 'success',
                            'update' => 'info',
                            default => 'gray',
                        }
                    ),

                Tables\Columns\TextColumn::make(
                    'region.name'
                )
                    ->label('Wilayah')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make(
                    'category.name'
                )
                    ->label('Kategori')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make(
                    'conservation_status'
                )
                    ->label('Status Kelestarian')
                    ->badge()
                    ->formatStateUsing(
                        fn (?string $state): string =>
                            Fish::CONSERVATION_STATUSES[
                                $state
                            ]
                            ?? 'Belum Ditentukan'
                    )
                    ->color(
                        fn (?string $state): string => match (
                            $state
                        ) {
                            'extinct' => 'gray',
                            'endangered' => 'danger',
                            'least_concern' => 'success',
                            default => 'warning',
                        }
                    )
                    ->toggleable(),

                Tables\Columns\TextColumn::make(
                    'biogeography'
                )
                    ->label('Biogeografi')
                    ->badge()
                    ->formatStateUsing(
                        fn (?string $state): string =>
                            Fish::BIOGEOGRAPHY_TYPES[
                                $state
                            ]
                            ?? 'Belum Ditentukan'
                    )
                    ->color(
                        fn (?string $state): string => match (
                            $state
                        ) {
                            'native' => 'info',
                            'endemic' => 'success',
                            'introduction' => 'warning',
                            default => 'gray',
                        }
                    )
                    ->toggleable(),

                Tables\Columns\TextColumn::make(
                    'request_status'
                )
                    ->label('Status Pengajuan')
                    ->badge()
                    ->formatStateUsing(
                        fn (?string $state): string =>
                            CreatureRequest::REQUEST_STATUSES[
                                $state
                            ]
                            ?? 'Tidak Diketahui'
                    )
                    ->color(
                        fn (?string $state): string => match (
                            $state
                        ) {
                            'pending' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                            default => 'gray',
                        }
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('reviewed_at')
                    ->label('Tanggal Pemeriksaan')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('Belum diperiksa')
                    ->sortable()
                    ->toggleable(
                        isToggledHiddenByDefault: true
                    ),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make(
                    'request_type'
                )
                    ->label('Jenis Pengajuan')
                    ->options(
                        CreatureRequest::REQUEST_TYPES
                    )
                    ->native(false),

                Tables\Filters\SelectFilter::make(
                    'conservation_status'
                )
                    ->label('Status Kelestarian')
                    ->options(
                        Fish::CONSERVATION_STATUSES
                    )
                    ->native(false),

                Tables\Filters\SelectFilter::make(
                    'biogeography'
                )
                    ->label('Biogeografi')
                    ->options(
                        Fish::BIOGEOGRAPHY_TYPES
                    )
                    ->native(false),

                Tables\Filters\SelectFilter::make(
                    'request_status'
                )
                    ->label('Status Pengajuan')
                    ->options(
                        CreatureRequest::REQUEST_STATUSES
                    )
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Periksa')
                    ->icon('heroicon-o-eye'),

                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(
                        fn (
                            CreatureRequest $record
                        ): bool =>
                            $record->request_status ===
                            'pending'
                    )
                    ->requiresConfirmation()
                    ->modalHeading(
                        'Setujui Pengajuan'
                    )
                    ->modalDescription(
                        'Data yang diajukan akan diterapkan ke data ikan dan dipublikasikan pada website.'
                    )
                    ->modalSubmitActionLabel('Setujui')
                    ->modalCancelActionLabel('Batal')
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
                            ->rows(3),
                    ])
                    ->action(function (
                        CreatureRequest $record,
                        array $data
                    ): void {
                        if (
                            $record->request_status !==
                            'pending'
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
                            $record->request_type === 'add'
                        ) {
                            Fish::create([
                                'region_id' =>
                                    $record->region_id,

                                'category_id' =>
                                    $record->category_id,

                                'name' =>
                                    $record->name,

                                'scientific_name' =>
                                    $record->scientific_name,

                                'image' =>
                                    $record->image,

                                'habitat' =>
                                    $record->habitat,

                                'food' =>
                                    $record->food,

                                'conservation_status' =>
                                    $record
                                        ->conservation_status,

                                'biogeography' =>
                                    $record->biogeography,

                                'characteristics' =>
                                    $record
                                        ->characteristics,

                                'description' =>
                                    $record->description,

                                'is_featured' => false,
                                'is_published' => true,
                            ]);
                        }

                        if (
                            $record->request_type ===
                                'update'
                            && $record->fish
                        ) {
                            $updateData = [
                                'region_id' =>
                                    $record->region_id,

                                'category_id' =>
                                    $record->category_id,

                                'name' =>
                                    $record->name,

                                'scientific_name' =>
                                    $record
                                        ->scientific_name,

                                'habitat' =>
                                    $record->habitat,

                                'food' =>
                                    $record->food,

                                'conservation_status' =>
                                    $record
                                        ->conservation_status,

                                'biogeography' =>
                                    $record->biogeography,

                                'characteristics' =>
                                    $record
                                        ->characteristics,

                                'description' =>
                                    $record->description,
                            ];

                            if ($record->image) {
                                $updateData['image'] =
                                    $record->image;
                            }

                            $record->fish->update(
                                $updateData
                            );
                        }

                        $record->update([
                            'request_status' =>
                                'approved',

                            'admin_note' =>
                                $data['admin_note']
                                ?? $record->admin_note,

                            'reviewed_by' =>
                                Auth::id(),

                            'reviewed_at' =>
                                now(),
                        ]);

                        Notification::make()
                            ->title(
                                'Pengajuan berhasil disetujui.'
                            )
                            ->body(
                                'Data ikan telah diperbarui dan dipublikasikan.'
                            )
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(
                        fn (
                            CreatureRequest $record
                        ): bool =>
                            $record->request_status ===
                            'pending'
                    )
                    ->requiresConfirmation()
                    ->modalHeading(
                        'Tolak Pengajuan'
                    )
                    ->modalDescription(
                        'Pengajuan akan ditolak dan alasan penolakan dapat dilihat oleh pengguna.'
                    )
                    ->modalSubmitActionLabel('Tolak')
                    ->modalCancelActionLabel('Batal')
                    ->form([
                        Forms\Components\Textarea::make(
                            'admin_note'
                        )
                            ->label('Alasan Penolakan')
                            ->placeholder(
                                'Jelaskan alasan pengajuan ditolak.'
                            )
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (
                        CreatureRequest $record,
                        array $data
                    ): void {
                        if (
                            $record->request_status !==
                            'pending'
                        ) {
                            Notification::make()
                                ->title(
                                    'Pengajuan sudah diproses.'
                                )
                                ->warning()
                                ->send();

                            return;
                        }

                        $record->update([
                            'request_status' =>
                                'rejected',

                            'admin_note' =>
                                $data['admin_note'],

                            'reviewed_by' =>
                                Auth::id(),

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
                    }),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading(
                'Belum ada pengajuan data'
            )
            ->emptyStateDescription(
                'Pengajuan dari pengguna akan ditampilkan pada halaman ini.'
            )
            ->emptyStateIcon(
                'heroicon-o-document-check'
            );
    }

    public static function getPages(): array
    {
        return [
            'index' =>
                Pages\ListCreatureRequests::route('/'),

            'edit' =>
                Pages\EditCreatureRequest::route(
                    '/{record}/edit'
                ),
        ];
    }
}
