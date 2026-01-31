<?php

namespace App\Filament\Resources\AttendanceSettings\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttendanceSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('start_time')
                    ->label('Jam Masuk'),
                TextColumn::make('end_time')
                    ->label('Jam Pulang'),
                TextColumn::make('late_tolerance_minutes')
                    ->label('Toleransi (menit)')
                    ->numeric(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
