<?php

namespace App\Filament\Admin\Resources\CreatureRequestResource\Pages;

use App\Filament\Admin\Resources\CreatureRequestResource;
use Filament\Resources\Pages\EditRecord;

class EditCreatureRequest extends EditRecord
{
    protected static string $resource = CreatureRequestResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
