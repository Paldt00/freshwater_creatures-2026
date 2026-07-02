<?php

namespace App\Filament\Admin\Resources\WebSettingResource\Pages;

use App\Filament\Admin\Resources\WebSettingResource;
use App\Models\WebSetting;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebSettings extends ListRecords
{
    protected static string $resource = WebSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->hidden(fn (): bool => WebSetting::query()->exists()),
        ];
    }
}
