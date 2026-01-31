<?php

namespace App\Filament\Resources\AttendanceRequests\Tables;

use App\Models\Attendance;
use App\Models\AttendanceRequest;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
                TextColumn::make('row_number')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('teacher.name')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Jenis')
                    ->formatStateUsing(fn(?string $state): string => match ($state) {
                        AttendanceRequest::TYPE_SICK => 'Sakit',
                        AttendanceRequest::TYPE_LEAVE => 'Izin',
                        AttendanceRequest::TYPE_OUTSIDE => 'Dinas Luar',
                        AttendanceRequest::TYPE_WFH => 'WFH',
                        AttendanceRequest::TYPE_CUTI => 'Cuti',
                        default => $state ?? '-',
                    }),
                TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date()
                    ->sortable(),
                TextColumn::make('reason')
                    ->label('Alasan')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        AttendanceRequest::STATUS_PENDING => 'warning',
                        AttendanceRequest::STATUS_APPROVED => 'success',
                        AttendanceRequest::STATUS_REJECTED => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
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
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn(AttendanceRequest $record): bool => $record->status === AttendanceRequest::STATUS_PENDING),
                Action::make('approve')
                    ->label('Setujui')
                    ->color('success')
                    ->visible(fn(AttendanceRequest $record): bool => $record->status === AttendanceRequest::STATUS_PENDING)
                    ->action(function (AttendanceRequest $record): void {
                        if ($record->status !== AttendanceRequest::STATUS_PENDING) {
                            return;
                        }

                        $start = $record->start_date ?? $record->date;
                        $end = $record->end_date ?? $record->date;
                        $conflict = Attendance::query()
                            ->where('teacher_id', $record->teacher_id)
                            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                            ->where(function ($q) {
                                $q->whereNotNull('check_in_time')
                                    ->orWhereNotNull('check_out_time')
                                    ->orWhere('check_in_status', 'H')
                                    ->orWhere('check_out_status', 'H');
                            })
                            ->exists();

                        if ($conflict) {
                            Notification::make()
                                ->title('Gagal menyetujui: ada absensi hadir pada periode tersebut')
                                ->danger()
                                ->send();
                            return;
                        }

                        DB::transaction(function () use ($record, $start, $end): void {
                            $statusCode = match ($record->type) {
                                AttendanceRequest::TYPE_SICK => 'S',
                                AttendanceRequest::TYPE_LEAVE => 'I',
                                AttendanceRequest::TYPE_OUTSIDE => 'D',
                                AttendanceRequest::TYPE_WFH => 'W',
                                AttendanceRequest::TYPE_CUTI => 'C',
                                default => 'I',
                            };

                            $period = new \DatePeriod($start, new \DateInterval('P1D'), $end->copy()->addDay());
                            foreach ($period as $date) {
                                Attendance::updateOrCreate(
                                    [
                                        'teacher_id' => $record->teacher_id,
                                        'date' => $date->format('Y-m-d'),
                                    ],
                                    [
                                        'check_in_status' => $statusCode,
                                        'check_out_status' => $statusCode,
                                        'check_in_time' => null,
                                        'check_out_time' => null,
                                        'late_minutes' => 0,
                                        'early_leave_minutes' => 0,
                                        'note' => $record->reason,
                                    ],
                                );
                            }

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
                    ->visible(fn(AttendanceRequest $record): bool => $record->status === AttendanceRequest::STATUS_PENDING)
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
