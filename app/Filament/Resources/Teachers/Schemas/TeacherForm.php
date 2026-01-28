<?php

namespace App\Filament\Resources\Teachers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TeacherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nip')
                    ->maxLength(50),
                TextInput::make('nuptk')
                    ->maxLength(50),
                TextInput::make('name')
                    ->required()
                    ->maxLength(120),
                Select::make('gender')
                    ->required()
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(30),
                Select::make('status')
                    ->required()
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Nonaktif',
                    ])
                    ->default('active'),
            ]);
    }
}
