<?php

namespace App\Filament\Admin\Resources\CreatureRequestResource\Pages;

use App\Filament\Admin\Resources\CreatureRequestResource;
use Filament\Resources\Pages\ListRecords;

class ListCreatureRequests extends ListRecords
{
    protected static string $resource = CreatureRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
