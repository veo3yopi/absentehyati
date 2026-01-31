<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\AttendanceRequest;
use App\Models\Teacher;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan Hari Ini';

    protected function getStats(): array
    {
        $today = now()->toDateString();
        $totalTeachers = Teacher::query()->count();

        $attendances = Attendance::query()
            ->whereDate('date', $today)
            ->get(['teacher_id', 'check_in_status', 'check_out_status']);

        $counts = [
            'H' => 0,
            'S' => 0,
            'I' => 0,
            'A' => 0,
            'D' => 0,
            'W' => 0,
            'C' => 0,
        ];

        foreach ($attendances as $attendance) {
            $status = $this->resolveFinalStatus($attendance->check_in_status, $attendance->check_out_status);
            $counts[$status] = ($counts[$status] ?? 0) + 1;
        }

        $missing = max($totalTeachers - $attendances->count(), 0);
        $counts['A'] += $missing;

        $pendingRequests = AttendanceRequest::query()
            ->where('status', AttendanceRequest::STATUS_PENDING)
            ->count();

        $nonPresent = $counts['S'] + $counts['I'] + $counts['D'] + $counts['W'] + $counts['C'];

        return [
            Stat::make('Total Guru', $totalTeachers)
                ->icon(Heroicon::OutlinedUsers),
            Stat::make('Hadir Hari Ini', $counts['H'])
                ->icon(Heroicon::OutlinedCheckCircle)
                ->color('success'),
            Stat::make('Izin/Sakit/Dinas/WFH/Cuti', $nonPresent)
                ->icon(Heroicon::OutlinedClipboardDocumentCheck)
                ->color('warning'),
            Stat::make('Alpa Hari Ini', $counts['A'])
                ->icon(Heroicon::OutlinedXCircle)
                ->color('danger'),
            Stat::make('Pengajuan Pending', $pendingRequests)
                ->icon(Heroicon::OutlinedInboxArrowDown)
                ->color('warning'),
        ];
    }

    private function resolveFinalStatus(?string $checkIn, ?string $checkOut): string
    {
        foreach ([$checkIn, $checkOut] as $code) {
            if (in_array($code, ['S', 'I', 'D', 'W', 'C', 'A'], true)) {
                return $code;
            }
        }

        if ($checkIn === 'H' || $checkOut === 'H') {
            return 'H';
        }

        return 'A';
    }
}
