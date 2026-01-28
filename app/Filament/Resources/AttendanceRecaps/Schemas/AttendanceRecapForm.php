<?php

namespace App\Filament\Resources\AttendanceRecaps\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AttendanceRecapForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('type')
                    ->required(),
                DatePicker::make('period_start')
                    ->required(),
                DatePicker::make('period_end')
                    ->required(),
                TextInput::make('month')
                    ->numeric(),
                TextInput::make('academic_year')
                    ->required(),
                TextInput::make('semester')
                    ->required(),
                TextInput::make('generated_by')
                    ->numeric(),
                DateTimePicker::make('generated_at'),
            ]);
    }
}
