<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\CreatureRequestResource;
use App\Models\CreatureRequest;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestCreatureRequests extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Request Data Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CreatureRequest::query()
                    ->with(['user', 'region', 'category', 'fish'])
                    ->latest()
                    ->limit(10)
            )
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
                    ->toggleable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('review')
                    ->label('Review')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->url(fn (CreatureRequest $record): string => CreatureRequestResource::getUrl('edit', [
                        'record' => $record,
                    ])),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
