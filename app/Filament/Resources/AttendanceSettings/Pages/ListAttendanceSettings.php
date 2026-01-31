<?php

namespace App\Filament\Resources\AttendanceSettings\Pages;

use App\Filament\Resources\AttendanceSettings\AttendanceSettingResource;
use App\Models\AttendanceSetting;
use Filament\Resources\Pages\ListRecords;

class ListAttendanceSettings extends ListRecords
{
    protected static string $resource = AttendanceSettingResource::class;

    public function mount(): void
    {
        parent::mount();

        $setting = AttendanceSetting::query()->first();

        if ($setting) {
            $this->redirect(AttendanceSettingResource::getUrl('edit', ['record' => $setting]));
            return;
        }

        $this->redirect(AttendanceSettingResource::getUrl('create'));
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
