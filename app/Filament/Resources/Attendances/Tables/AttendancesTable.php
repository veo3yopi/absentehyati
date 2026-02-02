<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row_number')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('teacher.name')
                    ->searchable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('attendance_late_status')
                    ->label('Status Kehadiran')
                    ->badge()
                    ->color(function ($record): string {
                        if ($record->check_in_status !== 'H') {
                            return 'gray';
                        }

                        return ($record->late_minutes ?? 0) > 0 ? 'warning' : 'success';
                    })
                    ->getStateUsing(function ($record): string {
                        if ($record->check_in_status !== 'H') {
                            return 'Tidak Hadir';
                        }

                        $lateMinutes = (int) ($record->late_minutes ?? 0);

                        return $lateMinutes > 0
                            ? "Terlambat {$lateMinutes} menit"
                            : 'Tidak Terlambat';
                    }),
                TextColumn::make('check_in_status')
                    ->searchable(),
                TextColumn::make('check_out_status')
                    ->searchable(),
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
                SelectFilter::make('late_status')
                    ->label('Status Kehadiran')
                    ->options([
                        'late' => 'Terlambat',
                        'on_time' => 'Tidak Terlambat',
                        'not_present' => 'Tidak Hadir',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value'] ?? null) {
                            'late' => $query
                                ->where('check_in_status', 'H')
                                ->where('late_minutes', '>', 0),
                            'on_time' => $query
                                ->where('check_in_status', 'H')
                                ->where('late_minutes', '=', 0),
                            'not_present' => $query->where('check_in_status', '!=', 'H'),
                            default => $query,
                        };
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
