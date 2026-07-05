<?php

namespace App\Filament\Admin\Resources\CreatureRequestResource\Pages;

use App\Filament\Admin\Resources\CreatureRequestResource;
use App\Models\CreatureRequest;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCreatureRequests extends ListRecords
{
    protected static string $resource =
        CreatureRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return 'Pengajuan Data';
    }

    public function getTabs(): array
    {
        return [
            'pending' => Tab::make(
                'Menunggu Pemeriksaan'
            )
                ->icon(
                    'heroicon-o-clock'
                )
                ->badge(
                    CreatureRequest::query()
                        ->where(
                            'request_status',
                            'pending'
                        )
                        ->count()
                )
                ->badgeColor('warning')
                ->modifyQueryUsing(
                    fn (
                        Builder $query
                    ): Builder =>
                        $query->where(
                            'request_status',
                            'pending'
                        )
                ),

            'all' => Tab::make('Semua')
                ->icon(
                    'heroicon-o-document-text'
                )
                ->badge(
                    CreatureRequest::query()
                        ->count()
                ),

            'approved' => Tab::make(
                'Disetujui'
            )
                ->icon(
                    'heroicon-o-check-circle'
                )
                ->badge(
                    CreatureRequest::query()
                        ->where(
                            'request_status',
                            'approved'
                        )
                        ->count()
                )
                ->badgeColor('success')
                ->modifyQueryUsing(
                    fn (
                        Builder $query
                    ): Builder =>
                        $query->where(
                            'request_status',
                            'approved'
                        )
                ),

            'rejected' => Tab::make(
                'Ditolak'
            )
                ->icon(
                    'heroicon-o-x-circle'
                )
                ->badge(
                    CreatureRequest::query()
                        ->where(
                            'request_status',
                            'rejected'
                        )
                        ->count()
                )
                ->badgeColor('danger')
                ->modifyQueryUsing(
                    fn (
                        Builder $query
                    ): Builder =>
                        $query->where(
                            'request_status',
                            'rejected'
                        )
                ),
        ];
    }

    public function getDefaultActiveTab():
        string|int|null
    {
        return 'pending';
    }
}
