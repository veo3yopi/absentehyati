<?php

namespace App\Filament\Resources\AttendanceSettings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class AttendanceSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TimePicker::make('start_time')
                    ->label('Jam Masuk')
                    ->seconds(false)
                    ->required(),
                TimePicker::make('end_time')
                    ->label('Jam Pulang')
                    ->seconds(false)
                    ->required(),
                TextInput::make('late_tolerance_minutes')
                    ->label('Toleransi Keterlambatan (menit)')
                    ->numeric()
                    ->minValue(0)
                    ->required(),
            ]);
    }
}
