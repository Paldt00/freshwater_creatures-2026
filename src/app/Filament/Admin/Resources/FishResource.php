<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FishResource\Pages;
use App\Models\Fish;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FishResource extends Resource
{
    protected static ?string $model = Fish::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Ikan';

    protected static ?string $modelLabel = 'Ikan';

    protected static ?string $pluralModelLabel = 'Ikan';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Ikan')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Ikan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->maxLength(255)
                            ->unique(table: 'fishes', column: 'slug', ignoreRecord: true)
                            ->helperText('Kosongkan jika ingin dibuat otomatis oleh sistem.'),

                        Forms\Components\TextInput::make('scientific_name')
                            ->label('Nama Ilmiah')
                            ->maxLength(255),

                        Forms\Components\Select::make('region_id')
                            ->label('Wilayah')
                            ->relationship('region', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar Ikan')
                            ->image()
                            ->directory('fishes')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->imageEditor(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Informasi Detail')
                    ->schema([
                        Forms\Components\TextInput::make('habitat')
                            ->label('Habitat')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('food')
                            ->label('Makanan')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('characteristics')
                            ->label('Ciri-ciri')
                            ->rows(5)
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status Publikasi')
                    ->schema([
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Tampilkan sebagai unggulan')
                            ->default(false),

                        Forms\Components\Toggle::make('is_published')
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
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Ikan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('scientific_name')
                    ->label('Nama Ilmiah')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('region.name')
                    ->label('Wilayah')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Publik')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('region_id')
                    ->label('Wilayah')
                    ->relationship('region', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Status Publikasi'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Unggulan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Ubah'),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFish::route('/'),
            'create' => Pages\CreateFish::route('/create'),
            'edit' => Pages\EditFish::route('/{record}/edit'),
        ];
    }
}
