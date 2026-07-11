<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\WebSettingResource\Pages;
use App\Models\WebSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WebSettingResource extends Resource
{
    protected static ?string $model = WebSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan Website';

    protected static ?string $modelLabel = 'Pengaturan Website';

    protected static ?string $pluralModelLabel = 'Pengaturan Website';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Website')
                    ->schema([
                        Forms\Components\TextInput::make('site_name')
                            ->label('Nama Website')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('hero_badge_text')
                            ->label('Teks Badge Hero')
                            ->placeholder('Contoh: Ensiklopedia Ikan Air Tawar')
                            ->helperText('Teks kecil yang tampil di bagian atas hero halaman beranda.')
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('logo')
                            ->label('Logo Website')
                            ->image()
                            ->directory('web-settings/logo')
                            ->visibility('public')
                            ->maxSize(1024)
                            ->imageEditor(),

                        Forms\Components\FileUpload::make('banner_image')
                            ->label('Gambar Banner')
                            ->image()
                            ->directory('web-settings/banner')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->imageEditor(),

                        Forms\Components\TextInput::make('hero_title')
                            ->label('Judul Hero')
                            ->placeholder('Contoh: Ensiklopedia Digital Ikan Air Tawar')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('hero_subtitle')
                            ->label('Deskripsi Hero')
                            ->placeholder('Masukkan deskripsi singkat yang tampil pada bagian hero halaman beranda.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Kontak')
                    ->schema([
                        Forms\Components\TextInput::make('contact_email')
                            ->label('Email Kontak')
                            ->placeholder('Contoh: freshwatercreatures@admin.com')
                            ->email()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Footer')
                    ->schema([
                        Forms\Components\Textarea::make('footer_text')
                            ->label('Teks Footer')
                            ->placeholder('Contoh: Website edukatif untuk mendukung dokumentasi keanekaragaman ikan air tawar.')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->square(),

                Tables\Columns\TextColumn::make('site_name')
                    ->label('Nama Website')
                    ->searchable(),

                Tables\Columns\TextColumn::make('hero_badge_text')
                    ->label('Badge Hero')
                    ->placeholder('Belum diisi')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('contact_email')
                    ->label('Email Kontak')
                    ->placeholder('Belum diisi')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebSettings::route('/'),
            'create' => Pages\CreateWebSetting::route('/create'),
            'edit' => Pages\EditWebSetting::route('/{record}/edit'),
        ];
    }
}
