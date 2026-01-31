<?php

namespace App\Filament\Resources\AttendanceSettings\Pages;

use App\Filament\Resources\AttendanceSettings\AttendanceSettingResource;
use Filament\Resources\Pages\EditRecord;

class EditAttendanceSetting extends EditRecord
{
    protected static string $resource = AttendanceSettingResource::class;
}
