<?php

namespace App\Filament\Resources\SchoolSettings\Pages;

use App\Filament\Resources\SchoolSettings\SchoolSettingResource;
use App\Models\SchoolSetting;
use Filament\Resources\Pages\ListRecords;

class ListSchoolSettings extends ListRecords
{
    protected static string $resource = SchoolSettingResource::class;

    public function mount(): void
    {
        parent::mount();

        $setting = SchoolSetting::query()->first();

        if ($setting) {
            $this->redirect(SchoolSettingResource::getUrl('edit', ['record' => $setting]));
            return;
        }

        $this->redirect(SchoolSettingResource::getUrl('create'));
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
