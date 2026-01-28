<?php

namespace App\Filament\Resources\AttendanceRequests\Tables;

use App\Models\Attendance;
use App\Models\AttendanceRequest;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AttendanceRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('teacher.name')
                    ->searchable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('check_in_status')
                    ->label('Masuk'),
                TextColumn::make('check_out_status')
                    ->label('Pulang'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        AttendanceRequest::STATUS_PENDING => 'warning',
                        AttendanceRequest::STATUS_APPROVED => 'success',
                        AttendanceRequest::STATUS_REJECTED => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        AttendanceRequest::STATUS_PENDING => 'Pending',
                        AttendanceRequest::STATUS_APPROVED => 'Disetujui',
                        AttendanceRequest::STATUS_REJECTED => 'Ditolak',
                        default => $state,
                    }),
                TextColumn::make('requester.name')
                    ->label('Pemohon')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('approver.name')
                    ->label('Penyetuju')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('approved_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        AttendanceRequest::STATUS_PENDING => 'Pending',
                        AttendanceRequest::STATUS_APPROVED => 'Disetujui',
                        AttendanceRequest::STATUS_REJECTED => 'Ditolak',
                    ])
                    ->default(AttendanceRequest::STATUS_PENDING),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn (AttendanceRequest $record): bool => $record->status === AttendanceRequest::STATUS_PENDING),
                Action::make('approve')
                    ->label('Setujui')
                    ->color('success')
                    ->visible(fn (AttendanceRequest $record): bool => $record->status === AttendanceRequest::STATUS_PENDING)
                    ->action(function (AttendanceRequest $record): void {
                        if ($record->status !== AttendanceRequest::STATUS_PENDING) {
                            return;
                        }

                        DB::transaction(function () use ($record): void {
                            Attendance::updateOrCreate(
                                [
                                    'teacher_id' => $record->teacher_id,
                                    'date' => $record->date,
                                ],
                                [
                                    'check_in_status' => $record->check_in_status,
                                    'check_out_status' => $record->check_out_status,
                                    'note' => $record->reason,
                                ],
                            );

                            $record->update([
                                'status' => AttendanceRequest::STATUS_APPROVED,
                                'approved_by' => auth()->id(),
                                'approved_at' => Carbon::now(),
                            ]);
                        });

                        Notification::make()
                            ->title('Permohonan disetujui')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->color('danger')
                    ->visible(fn (AttendanceRequest $record): bool => $record->status === AttendanceRequest::STATUS_PENDING)
                    ->action(function (AttendanceRequest $record): void {
                        if ($record->status !== AttendanceRequest::STATUS_PENDING) {
                            return;
                        }

                        DB::transaction(function () use ($record): void {
                            $record->update([
                                'status' => AttendanceRequest::STATUS_REJECTED,
                                'approved_by' => auth()->id(),
                                'approved_at' => Carbon::now(),
                            ]);
                        });

                        Notification::make()
                            ->title('Permohonan ditolak')
                            ->danger()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
