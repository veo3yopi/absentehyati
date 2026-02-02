<?php

namespace App\Filament\Resources\AttendanceSettings\Pages;

use App\Filament\Resources\AttendanceSettings\AttendanceSettingResource;
use Filament\Resources\Pages\EditRecord;

class EditAttendanceSetting extends EditRecord
{
    protected static string $resource = AttendanceSettingResource::class;

    public function getHeading(): string
    {
        return 'Pengarutan Absensi';
    }

    public function getTitle(): string
    {
        return 'Pengarutan Absensi';
    }

    public function getBreadcrumb(): string
    {
        return 'Pengarutan Absensi';
    }
}
