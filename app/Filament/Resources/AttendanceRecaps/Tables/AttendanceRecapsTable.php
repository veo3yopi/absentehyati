<?php

namespace App\Filament\Resources\AttendanceRecaps\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttendanceRecapsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row_number')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('period_start')
                    ->date()
                    ->sortable(),
                TextColumn::make('period_end')
                    ->date()
                    ->sortable(),
                TextColumn::make('month')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('academic_year')
                    ->searchable(),
                TextColumn::make('semester')
                    ->searchable(),
                TextColumn::make('generated_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('generated_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([])
            ->toolbarActions([
                //
            ]);
    }
}
