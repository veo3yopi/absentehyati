<?php

namespace App\Filament\Resources\AttendanceRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class AttendanceRequestForm
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
                        fn (Get $get, ?Model $record) => Rule::unique('attendance_requests', 'date')
                            ->where('teacher_id', $get('teacher_id'))
                            ->where('status', 'pending')
                            ->ignore($record),
                    ]),
                Select::make('check_in_status')
                    ->required()
                    ->options([
                        'H' => 'Hadir',
                        'S' => 'Sakit',
                        'I' => 'Izin',
                        'A' => 'Alfa',
                    ])
                    ->default('H'),
                Select::make('check_out_status')
                    ->required()
                    ->options([
                        'H' => 'Hadir',
                        'S' => 'Sakit',
                        'I' => 'Izin',
                        'A' => 'Alfa',
                    ])
                    ->default('H'),
                Textarea::make('reason')
                    ->columnSpanFull(),
                Hidden::make('status')
                    ->default('pending'),
                Hidden::make('requested_by')
                    ->default(fn () => auth()->id()),
            ]);
    }
}
