<?php

namespace App\Filament\Resources\AttendanceRequests\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;

class AttendanceRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->required(),
                Select::make('type')
                    ->required()
                    ->options([
                        'S' => 'Sakit',
                        'I' => 'Izin',
                        'D' => 'Dinas Luar',
                        'W' => 'WFH',
                        'C' => 'Cuti',
                    ]),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->rules(['after_or_equal:start_date']),
                Textarea::make('reason')
                    ->columnSpanFull(),
                Hidden::make('date')
                    ->dehydrateStateUsing(fn (Get $get) => $get('start_date')),
                Hidden::make('status')
                    ->default('pending'),
                Hidden::make('requested_by')
                    ->default(fn () => auth()->id()),
            ]);
    }
}
