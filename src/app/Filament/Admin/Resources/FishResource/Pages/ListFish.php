<?php

namespace App\Filament\Admin\Resources\FishResource\Pages;

use App\Filament\Admin\Resources\FishResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFish extends ListRecords
{
    protected static string $resource = FishResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Ikan'),
        ];
    }
}
