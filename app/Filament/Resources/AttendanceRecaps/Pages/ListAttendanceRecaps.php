<?php

namespace App\Filament\Resources\AttendanceRecaps\Pages;

use App\Filament\Resources\AttendanceRecaps\AttendanceRecapResource;
use App\Models\Attendance;
use App\Models\AttendanceRecap;
use App\Models\AttendanceRecapRow;
use App\Models\SchoolSetting;
use App\Models\Teacher;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ListAttendanceRecaps extends ListRecords
{
    protected static string $resource = AttendanceRecapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate')
                ->label('Generate Rekap')
                ->form([
                    Select::make('type')
                        ->required()
                        ->options([
                            AttendanceRecap::TYPE_MONTHLY => 'Per Bulan',
                            AttendanceRecap::TYPE_SEMESTER => 'Per Semester',
                        ])
                        ->default(AttendanceRecap::TYPE_MONTHLY)
                        ->live(),
                    Select::make('month')
                        ->options([
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember',
                        ])
                        ->visible(fn ($get) => $get('type') === AttendanceRecap::TYPE_MONTHLY)
                        ->required(fn ($get) => $get('type') === AttendanceRecap::TYPE_MONTHLY),
                    TextInput::make('year')
                        ->numeric()
                        ->default((int) now()->format('Y'))
                        ->visible(fn ($get) => $get('type') === AttendanceRecap::TYPE_MONTHLY)
                        ->required(fn ($get) => $get('type') === AttendanceRecap::TYPE_MONTHLY),
                    DatePicker::make('period_start')
                        ->visible(fn ($get) => $get('type') === AttendanceRecap::TYPE_SEMESTER)
                        ->required(fn ($get) => $get('type') === AttendanceRecap::TYPE_SEMESTER),
                    DatePicker::make('period_end')
                        ->visible(fn ($get) => $get('type') === AttendanceRecap::TYPE_SEMESTER)
                        ->required(fn ($get) => $get('type') === AttendanceRecap::TYPE_SEMESTER),
                    TextInput::make('academic_year')
                        ->label('Tahun Pelajaran')
                        ->helperText('Contoh: 2025/2026')
                        ->maxLength(20),
                    Select::make('semester')
                        ->options([
                            'Ganjil' => 'Ganjil',
                            'Genap' => 'Genap',
                        ]),
                ])
                ->action(function (array $data): void {
                    $setting = SchoolSetting::query()->first();
                    if (! $setting) {
                        Notification::make()
                            ->title('Setting sekolah belum diisi')
                            ->danger()
                            ->send();
                        return;
                    }

                    $type = $data['type'];
                    $academicYear = $data['academic_year'] ?: $setting->academic_year;
                    $semester = $data['semester'] ?: $setting->semester;
                    $month = null;
                    if ($type === AttendanceRecap::TYPE_MONTHLY) {
                        $month = (int) $data['month'];
                        $year = (int) $data['year'];
                        $periodStart = Carbon::create($year, $month, 1)->startOfMonth();
                        $periodEnd = (clone $periodStart)->endOfMonth();
                    } else {
                        $periodStart = Carbon::parse($data['period_start'])->startOfDay();
                        $periodEnd = Carbon::parse($data['period_end'])->endOfDay();
                        if ($periodEnd->lt($periodStart)) {
                            Notification::make()
                                ->title('Periode tidak valid')
                                ->danger()
                                ->send();
                            return;
                        }
                    }

                    DB::transaction(function () use (
                        $type,
                        $periodStart,
                        $periodEnd,
                        $month,
                        $academicYear,
                        $semester
                    ): void {
                        $recap = AttendanceRecap::query()
                            ->where('type', $type)
                            ->whereDate('period_start', $periodStart->toDateString())
                            ->whereDate('period_end', $periodEnd->toDateString())
                            ->where('academic_year', $academicYear)
                            ->where('semester', $semester)
                            ->when($month !== null, fn ($q) => $q->where('month', $month))
                            ->first();

                        if ($recap) {
                            AttendanceRecapRow::query()
                                ->where('attendance_recap_id', $recap->id)
                                ->delete();
                            $recap->update([
                                'month' => $month,
                                'generated_by' => auth()->id(),
                                'generated_at' => Carbon::now(),
                            ]);
                        } else {
                            $recap = AttendanceRecap::create([
                                'type' => $type,
                                'period_start' => $periodStart,
                                'period_end' => $periodEnd,
                                'month' => $month,
                                'academic_year' => $academicYear,
                                'semester' => $semester,
                                'generated_by' => auth()->id(),
                                'generated_at' => Carbon::now(),
                            ]);
                        }

                        $totals = Attendance::query()
                            ->select([
                                'teacher_id',
                                DB::raw("SUM(CASE WHEN check_in_status = 'H' THEN 1 ELSE 0 END) as in_h"),
                                DB::raw("SUM(CASE WHEN check_in_status = 'S' THEN 1 ELSE 0 END) as in_s"),
                                DB::raw("SUM(CASE WHEN check_in_status = 'I' THEN 1 ELSE 0 END) as in_i"),
                                DB::raw("SUM(CASE WHEN check_in_status = 'A' THEN 1 ELSE 0 END) as in_a"),
                                DB::raw("SUM(CASE WHEN check_out_status = 'H' THEN 1 ELSE 0 END) as out_h"),
                                DB::raw("SUM(CASE WHEN check_out_status = 'S' THEN 1 ELSE 0 END) as out_s"),
                                DB::raw("SUM(CASE WHEN check_out_status = 'I' THEN 1 ELSE 0 END) as out_i"),
                                DB::raw("SUM(CASE WHEN check_out_status = 'A' THEN 1 ELSE 0 END) as out_a"),
                            ])
                            ->whereBetween('date', [$periodStart->toDateString(), $periodEnd->toDateString()])
                            ->groupBy('teacher_id')
                            ->get()
                            ->keyBy('teacher_id');

                        $rows = [];
                        $teachers = Teacher::query()->orderBy('name')->get();
                        foreach ($teachers as $teacher) {
                            $row = $totals->get($teacher->id);
                            $rows[] = [
                                'attendance_recap_id' => $recap->id,
                                'teacher_id' => $teacher->id,
                                'in_h' => (int) ($row->in_h ?? 0),
                                'in_s' => (int) ($row->in_s ?? 0),
                                'in_i' => (int) ($row->in_i ?? 0),
                                'in_a' => (int) ($row->in_a ?? 0),
                                'out_h' => (int) ($row->out_h ?? 0),
                                'out_s' => (int) ($row->out_s ?? 0),
                                'out_i' => (int) ($row->out_i ?? 0),
                                'out_a' => (int) ($row->out_a ?? 0),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }

                        if ($rows) {
                            AttendanceRecapRow::query()->insert($rows);
                        }
                    });

                    Notification::make()
                        ->title('Rekap berhasil dibuat')
                        ->success()
                        ->send();
                }),
        ];
    }
}
