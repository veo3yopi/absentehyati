<?php

namespace App\Filament\Resources\SchoolSettings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SchoolSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('school_name')
                    ->required(),
                Textarea::make('address')
                    ->columnSpanFull(),
                TextInput::make('academic_year')
                    ->required(),
                TextInput::make('semester')
                    ->required(),
            ]);
    }
}
