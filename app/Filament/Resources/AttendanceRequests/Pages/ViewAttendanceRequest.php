<?php

namespace App\Filament\Resources\AttendanceRequests\Pages;

use App\Filament\Resources\AttendanceRequests\AttendanceRequestResource;
use App\Models\Attendance;
use App\Models\AttendanceRequest;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ViewAttendanceRequest extends ViewRecord
{
    protected static string $resource = AttendanceRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Setujui')
                ->color('success')
                ->visible(fn (): bool => $this->record->status === AttendanceRequest::STATUS_PENDING)
                ->action(function (): void {
                    /** @var AttendanceRequest $record */
                    $record = $this->record;

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
                ->visible(fn (): bool => $this->record->status === AttendanceRequest::STATUS_PENDING)
                ->action(function (): void {
                    /** @var AttendanceRequest $record */
                    $record = $this->record;

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
        ];
    }
}
