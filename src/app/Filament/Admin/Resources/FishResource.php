<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FishResource\Pages;
use App\Models\Fish;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FishResource extends Resource
{
    protected static ?string $model = Fish::class;

    protected static ?string $navigationIcon =
        'heroicon-o-sparkles';

    protected static ?string $navigationGroup =
        'Konten';

    protected static ?string $navigationLabel =
        'Ikan';

    protected static ?string $modelLabel =
        'Ikan';

    protected static ?string $pluralModelLabel =
        'Ikan';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute =
        'name';

    public static function getNavigationBadge(): ?string
    {
        $total = self::applyIncompleteQuery(
            Fish::query()
        )->count();

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
        return 'Jumlah data ikan yang belum lengkap';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(
                    'Identitas Ikan'
                )
                    ->description(
                        'Lengkapi informasi utama ikan.'
                    )
                    ->schema([
                        Forms\Components\TextInput::make(
                            'name'
                        )
                            ->label('Nama Ikan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make(
                            'slug'
                        )
                            ->label('Slug')
                            ->maxLength(255)
                            ->unique(
                                table: 'fishes',
                                column: 'slug',
                                ignoreRecord: true
                            )
                            ->helperText(
                                'Kosongkan jika ingin dibuat otomatis oleh sistem.'
                            ),

                        Forms\Components\TextInput::make(
                            'scientific_name'
                        )
                            ->label('Nama Ilmiah')
                            ->required()
                            ->maxLength(255)
                            ->placeholder(
                                'Contoh: Scleropages formosus'
                            ),

                        Forms\Components\Select::make(
                            'region_id'
                        )
                            ->label('Wilayah')
                            ->relationship(
                                'region',
                                'name'
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),

                        Forms\Components\Select::make(
                            'category_id'
                        )
                            ->label('Kategori')
                            ->relationship(
                                'category',
                                'name'
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),

                        Forms\Components\FileUpload::make(
                            'image'
                        )
                            ->label('Gambar Ikan')
                            ->image()
                            ->disk('public')
                            ->directory('fishes')
                            ->visibility('public')
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ])
                            ->maxSize(2048)
                            ->imageEditor()
                            ->required()
                            ->helperText(
                                'Format JPG, PNG, atau WebP. Maksimal 2 MB.'
                            )
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(
                    'Informasi Detail'
                )
                    ->description(
                        'Informasi habitat, makanan, kelestarian, dan ciri-ciri ikan.'
                    )
                    ->schema([
                        Forms\Components\TextInput::make(
                            'habitat'
                        )
                            ->label('Habitat')
                            ->required()
                            ->maxLength(255)
                            ->placeholder(
                                'Contoh: Sungai, rawa, dan danau'
                            ),

                        Forms\Components\TextInput::make(
                            'food'
                        )
                            ->label('Makanan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder(
                                'Contoh: Serangga, ikan kecil, dan udang'
                            ),

                        Forms\Components\Select::make(
                            'conservation_status'
                        )
                            ->label('Status Kelestarian')
                            ->options(
                                Fish::CONSERVATION_STATUSES
                            )
                            ->native(false)
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make(
                            'biogeography'
                        )
                            ->label('Biogeografi')
                            ->options(
                                Fish::BIOGEOGRAPHY_TYPES
                            )
                            ->native(false)
                            ->searchable()
                            ->required(),

                        Forms\Components\Textarea::make(
                            'characteristics'
                        )
                            ->label('Ciri-ciri')
                            ->required()
                            ->rows(5)
                            ->placeholder(
                                'Jelaskan bentuk tubuh, warna, ukuran, dan karakteristik lainnya.'
                            )
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make(
                            'description'
                        )
                            ->label('Deskripsi')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'bulletList',
                                'orderedList',
                                'undo',
                                'redo',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(
                    'Status Publikasi'
                )
                    ->schema([
                        Forms\Components\Toggle::make(
                            'is_featured'
                        )
                            ->label(
                                'Tampilkan sebagai unggulan'
                            )
                            ->default(false),

                        Forms\Components\Toggle::make(
                            'is_published'
                        )
                            ->label('Publikasikan')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make(
                    'image'
                )
                    ->label('Gambar')
                    ->disk('public')
                    ->square()
                    ->size(55),

                Tables\Columns\TextColumn::make(
                    'name'
                )
                    ->label('Nama Ikan')
                    ->description(
                        fn (Fish $record): ?string =>
                            $record->scientific_name
                    )
                    ->searchable([
                        'name',
                        'scientific_name',
                    ])
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make(
                    'region.name'
                )
                    ->label('Wilayah')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make(
                    'category.name'
                )
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),

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
                        fn (?string $state): string =>
                            match ($state) {
                                'extinct' => 'gray',
                                'endangered' => 'danger',
                                'least_concern' => 'success',
                                default => 'warning',
                            }
                    )
                    ->sortable(),

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
                        fn (?string $state): string =>
                            match ($state) {
                                'native' => 'info',
                                'endemic' => 'success',
                                'introduction' => 'warning',
                                default => 'gray',
                            }
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make(
                    'data_completeness'
                )
                    ->label('Kelengkapan Data')
                    ->getStateUsing(
                        fn (Fish $record): string =>
                            self::isComplete($record)
                                ? 'Lengkap'
                                : 'Belum Lengkap'
                    )
                    ->description(
                        function (Fish $record): ?string {
                            $missingFields =
                                self::getMissingFields(
                                    $record
                                );

                            if ($missingFields === []) {
                                return null;
                            }

                            return 'Kosong: '
                                . implode(
                                    ', ',
                                    $missingFields
                                );
                        }
                    )
                    ->badge()
                    ->color(
                        fn (
                            Fish $record
                        ): string =>
                            self::isComplete($record)
                                ? 'success'
                                : 'warning'
                    )
                    ->wrap(),

                Tables\Columns\IconColumn::make(
                    'is_featured'
                )
                    ->label('Unggulan')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\IconColumn::make(
                    'is_published'
                )
                    ->label('Publik')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\TextColumn::make(
                    'updated_at'
                )
                    ->label('Diperbarui')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(
                        isToggledHiddenByDefault: true
                    ),
            ])
            ->filters([
                Tables\Filters\Filter::make(
                    'incomplete_data'
                )
                    ->label('Data Belum Lengkap')
                    ->query(
                        fn (
                            Builder $query
                        ): Builder =>
                            self::applyIncompleteQuery(
                                $query
                            )
                    ),

                Tables\Filters\SelectFilter::make(
                    'region_id'
                )
                    ->label('Wilayah')
                    ->relationship(
                        'region',
                        'name'
                    )
                    ->searchable()
                    ->preload()
                    ->native(false),

                Tables\Filters\SelectFilter::make(
                    'category_id'
                )
                    ->label('Kategori')
                    ->relationship(
                        'category',
                        'name'
                    )
                    ->searchable()
                    ->preload()
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

                Tables\Filters\TernaryFilter::make(
                    'is_published'
                )
                    ->label('Status Publikasi')
                    ->trueLabel('Dipublikasikan')
                    ->falseLabel(
                        'Tidak Dipublikasikan'
                    )
                    ->placeholder('Semua Data'),

                Tables\Filters\TernaryFilter::make(
                    'is_featured'
                )
                    ->label('Status Unggulan')
                    ->trueLabel('Unggulan')
                    ->falseLabel(
                        'Bukan Unggulan'
                    )
                    ->placeholder('Semua Data'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(
                        fn (Fish $record): string =>
                            self::isComplete($record)
                                ? 'Ubah'
                                : 'Lengkapi'
                    )
                    ->icon(
                        fn (Fish $record): string =>
                            self::isComplete($record)
                                ? 'heroicon-o-pencil-square'
                                : 'heroicon-o-exclamation-triangle'
                    )
                    ->color(
                        fn (Fish $record): string =>
                            self::isComplete($record)
                                ? 'primary'
                                : 'warning'
                    ),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ])
            ->defaultSort('updated_at', 'desc')
            ->emptyStateHeading(
                'Belum ada data ikan'
            )
            ->emptyStateDescription(
                'Tambahkan data ikan air tawar untuk mulai mengelola informasi.'
            )
            ->emptyStateIcon(
                'heroicon-o-sparkles'
            );
    }

    private static function isComplete(
        Fish $fish
    ): bool {
        return self::getMissingFields($fish) === [];
    }

    private static function getMissingFields(
        Fish $fish
    ): array {
        $fields = [
            'image' => 'gambar',
            'scientific_name' => 'nama ilmiah',
            'habitat' => 'habitat',
            'food' => 'makanan',
            'conservation_status' =>
                'status kelestarian',
            'biogeography' => 'biogeografi',
            'characteristics' => 'ciri-ciri',
            'description' => 'deskripsi',
        ];

        $missingFields = [];

        foreach ($fields as $field => $label) {
            $value = $fish->{$field};

            if ($field === 'description') {
                $value = trim(
                    strip_tags((string) $value)
                );
            }

            if (
                $value === null
                || trim((string) $value) === ''
            ) {
                $missingFields[] = $label;
            }
        }

        return $missingFields;
    }

    private static function applyIncompleteQuery(
        Builder $query
    ): Builder {
        return $query->where(
            function (Builder $query): void {
                $query
                    ->whereNull('image')
                    ->orWhere('image', '')
                    ->orWhereNull(
                        'scientific_name'
                    )
                    ->orWhere(
                        'scientific_name',
                        ''
                    )
                    ->orWhereNull('habitat')
                    ->orWhere('habitat', '')
                    ->orWhereNull('food')
                    ->orWhere('food', '')
                    ->orWhereNull(
                        'conservation_status'
                    )
                    ->orWhere(
                        'conservation_status',
                        ''
                    )
                    ->orWhereNull(
                        'biogeography'
                    )
                    ->orWhere(
                        'biogeography',
                        ''
                    )
                    ->orWhereNull(
                        'characteristics'
                    )
                    ->orWhere(
                        'characteristics',
                        ''
                    )
                    ->orWhereNull('description')
                    ->orWhere('description', '')
                    ->orWhere(
                        'description',
                        '<p></p>'
                    )
                    ->orWhere(
                        'description',
                        '<p><br></p>'
                    );
            }
        );
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' =>
                Pages\ListFish::route('/'),

            'create' =>
                Pages\CreateFish::route(
                    '/create'
                ),

            'edit' =>
                Pages\EditFish::route(
                    '/{record}/edit'
                ),
        ];
    }
}
