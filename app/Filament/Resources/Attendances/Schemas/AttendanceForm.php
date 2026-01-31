<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

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
                    ->required()
                    ->rules([
                        fn (Get $get, ?Model $record) => Rule::unique('attendances', 'date')
                            ->where('teacher_id', $get('teacher_id'))
                            ->ignore($record),
                    ]),
                Select::make('check_in_status')
                    ->required()
                    ->options([
                        'H' => 'Hadir',
                        'S' => 'Sakit',
                        'I' => 'Izin',
                        'A' => 'Alfa',
                        'D' => 'Dinas Luar',
                        'W' => 'WFH',
                        'C' => 'Cuti',
                    ])
                    ->default('H'),
                Select::make('check_out_status')
                    ->required()
                    ->options([
                        'H' => 'Hadir',
                        'S' => 'Sakit',
                        'I' => 'Izin',
                        'A' => 'Alfa',
                        'D' => 'Dinas Luar',
                        'W' => 'WFH',
                        'C' => 'Cuti',
                    ])
                    ->default('H'),
                Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }
}
