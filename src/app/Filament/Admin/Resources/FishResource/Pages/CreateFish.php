<?php

namespace App\Filament\Admin\Resources\FishResource\Pages;

use App\Filament\Admin\Resources\FishResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFish extends CreateRecord
{
    protected static string $resource = FishResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
