<?php

namespace App\Filament\Resources\Teachers\Schemas;

use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

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
                TextInput::make('user_email')
                    ->label('Email Login')
                    ->email()
                    ->required()
                    ->rules([
                        fn (Get $get, ?Model $record) => Rule::unique('users', 'email')
                            ->ignore($record?->user?->id),
                    ]),
                TextInput::make('user_name')
                    ->label('Nama Akun')
                    ->maxLength(120),
                TextInput::make('user_password')
                    ->label('Password')
                    ->password()
                    ->minLength(6)
                    ->required(fn ($record) => $record === null)
                    ->dehydrated(fn ($state) => filled($state)),
                TextInput::make('user_password_confirmation')
                    ->label('Konfirmasi Password')
                    ->password()
                    ->same('user_password')
                    ->dehydrated(false),
            ]);
    }
}
