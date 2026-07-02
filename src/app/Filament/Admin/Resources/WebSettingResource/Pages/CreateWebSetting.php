<?php

namespace App\Filament\Admin\Resources\WebSettingResource\Pages;

use App\Filament\Admin\Resources\WebSettingResource;
use App\Models\WebSetting;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateWebSetting extends CreateRecord
{
    protected static string $resource = WebSettingResource::class;

    protected function beforeCreate(): void
    {
        if (WebSetting::query()->exists()) {
            Notification::make()
                ->title('Pengaturan website sudah tersedia.')
                ->danger()
                ->send();

            $this->halt();
        }
    }
}
