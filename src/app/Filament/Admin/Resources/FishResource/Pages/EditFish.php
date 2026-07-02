<?php

namespace App\Filament\Admin\Resources\FishResource\Pages;

use App\Filament\Admin\Resources\FishResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFish extends EditRecord
{
    protected static string $resource = FishResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Ikan'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
