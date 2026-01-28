<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->required(),
                DatePicker::make('date')
                    ->required(),
                TextInput::make('check_in_status')
                    ->required()
                    ->default('H'),
                TextInput::make('check_out_status')
                    ->required()
                    ->default('H'),
                Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }
}
