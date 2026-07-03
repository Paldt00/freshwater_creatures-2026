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

class CreatureRequestResource extends Resource
{
    protected static ?string $model = CreatureRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Request Data';

    protected static ?string $modelLabel = 'Request Data';

    protected static ?string $pluralModelLabel = 'Request Data';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Request')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Pengguna')
                            ->relationship('user', 'name')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('fish_id')
                            ->label('Ikan Lama')
                            ->relationship('fish', 'name')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('request_type')
                            ->label('Jenis Request')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('request_status')
                            ->label('Status')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Data yang Diajukan')
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

                        Forms\Components\TextInput::make('scientific_name')
                            ->label('Nama Ilmiah')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar')
                            ->image()
                            ->directory('request-fishes')
                            ->visibility('public')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('habitat')
                            ->label('Habitat')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('food')
                            ->label('Makanan')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Textarea::make('characteristics')
                            ->label('Ciri-ciri')
                            ->rows(4)
                            ->columnSpanFull()
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(5)
                            ->columnSpanFull()
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Catatan')
                    ->schema([
                        Forms\Components\Textarea::make('request_note')
                            ->label('Catatan Pengguna')
                            ->rows(4)
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Textarea::make('admin_note')
                            ->label('Catatan Admin')
                            ->rows(4),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Ikan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('request_type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'add' => 'Tambah',
                        'update' => 'Ubah',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'add' => 'success',
                        'update' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('request_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('region.name')
                    ->label('Wilayah')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('request_type')
                    ->label('Jenis Request')
                    ->options([
                        'add' => 'Tambah Data',
                        'update' => 'Ubah Data',
                    ]),

                Tables\Filters\SelectFilter::make('request_status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Review'),

                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (CreatureRequest $record): bool => $record->request_status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Request')
                    ->modalDescription('Data akan diterapkan ke data ikan.')
                    ->form([
                        Forms\Components\Textarea::make('admin_note')
                            ->label('Catatan Admin')
                            ->rows(3),
                    ])
                    ->action(function (CreatureRequest $record, array $data): void {
                        if ($record->request_type === 'add') {
                            Fish::create([
                                'region_id' => $record->region_id,
                                'category_id' => $record->category_id,
                                'name' => $record->name,
                                'scientific_name' => $record->scientific_name,
                                'image' => $record->image,
                                'habitat' => $record->habitat,
                                'food' => $record->food,
                                'characteristics' => $record->characteristics,
                                'description' => $record->description,
                                'is_featured' => false,
                                'is_published' => true,
                            ]);
                        }

                        if ($record->request_type === 'update' && $record->fish) {
                            $updateData = [
                                'region_id' => $record->region_id,
                                'category_id' => $record->category_id,
                                'name' => $record->name,
                                'scientific_name' => $record->scientific_name,
                                'habitat' => $record->habitat,
                                'food' => $record->food,
                                'characteristics' => $record->characteristics,
                                'description' => $record->description,
                            ];

                            if ($record->image) {
                                $updateData['image'] = $record->image;
                            }

                            $record->fish->update($updateData);
                        }

                        $record->update([
                            'request_status' => 'approved',
                            'admin_note' => $data['admin_note'] ?? $record->admin_note,
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Request berhasil disetujui.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (CreatureRequest $record): bool => $record->request_status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Request')
                    ->form([
                        Forms\Components\Textarea::make('admin_note')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (CreatureRequest $record, array $data): void {
                        $record->update([
                            'request_status' => 'rejected',
                            'admin_note' => $data['admin_note'],
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Request berhasil ditolak.')
                            ->danger()
                            ->send();
                    }),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCreatureRequests::route('/'),
            'edit' => Pages\EditCreatureRequest::route('/{record}/edit'),
        ];
    }
}
