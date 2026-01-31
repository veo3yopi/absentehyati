<?php

namespace App\Filament\Resources\AttendanceRecaps\Pages;

use App\Filament\Resources\AttendanceRecaps\AttendanceRecapResource;
use App\Models\Attendance;
use App\Models\AttendanceRecap;
use App\Models\AttendanceRecapRow;
use App\Models\SchoolSetting;
use App\Models\Teacher;
use Dompdf\Dompdf;
use Dompdf\Options;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                ->action(function (array $data) {
                    $setting = SchoolSetting::query()->first();
                    if (! $setting) {
                        Notification::make()
                            ->title('Setting sekolah belum diisi')
                            ->danger()
                            ->send();
                        return;
                    }

                    $recap = null;
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
                        &$recap,
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

                        $attendanceByTeacher = Attendance::query()
                            ->whereBetween('date', [$periodStart->toDateString(), $periodEnd->toDateString()])
                            ->get()
                            ->groupBy('teacher_id')
                            ->map(function ($items) {
                                return $items->keyBy(fn ($item) => $item->date->toDateString());
                            });

                        $dates = [];
                        $cursor = $periodStart->copy()->startOfDay();
                        $endDate = $periodEnd->copy()->startOfDay();
                        while ($cursor->lte($endDate)) {
                            $dates[] = $cursor->toDateString();
                            $cursor->addDay();
                        }

                        $rows = [];
                        $teachers = Teacher::query()->orderBy('name')->get();
                        foreach ($teachers as $teacher) {
                            $counts = [
                                'H' => 0,
                                'S' => 0,
                                'I' => 0,
                                'A' => 0,
                                'D' => 0,
                                'W' => 0,
                                'C' => 0,
                            ];

                            $teacherAttendance = $attendanceByTeacher->get($teacher->id, collect());
                            foreach ($dates as $date) {
                                $attendance = $teacherAttendance->get($date);
                                $status = 'A';
                                if ($attendance) {
                                    $status = null;
                                    foreach ([
                                        $attendance->check_in_status,
                                        $attendance->check_out_status,
                                    ] as $code) {
                                        if (in_array($code, ['S', 'I', 'D', 'W', 'C', 'A'], true)) {
                                            $status = $code;
                                            break;
                                        }
                                    }
                                    if (! $status) {
                                        $status = ($attendance->check_in_status === 'H' || $attendance->check_out_status === 'H')
                                            ? 'H'
                                            : 'A';
                                    }
                                }

                                $counts[$status] = ($counts[$status] ?? 0) + 1;
                            }

                            $rows[] = [
                                'attendance_recap_id' => $recap->id,
                                'teacher_id' => $teacher->id,
                                'in_h' => $counts['H'],
                                'in_s' => $counts['S'],
                                'in_i' => $counts['I'],
                                'in_a' => $counts['A'],
                                'in_d' => $counts['D'],
                                'in_w' => $counts['W'],
                                'in_c' => $counts['C'],
                                'out_h' => 0,
                                'out_s' => 0,
                                'out_i' => 0,
                                'out_a' => 0,
                                'out_d' => 0,
                                'out_w' => 0,
                                'out_c' => 0,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }

                        if ($rows) {
                            AttendanceRecapRow::query()->insert($rows);
                        }
                    });

                    if (! $recap) {
                        Notification::make()
                            ->title('Gagal membuat rekap')
                            ->danger()
                            ->send();
                        return;
                    }

                    $recap->load(['rows.teacher', 'generator']);
                    $fileBase = 'rekap-absensi-' . ($recap->academic_year ?? 'periode') . '-' . ($recap->semester ?? '');
                    $filename = Str::slug($fileBase, '-') . '.pdf';

                    $html = view('admin.attendance_recaps.pdf', [
                        'recap' => $recap,
                        'rows' => $recap->rows,
                        'school' => $setting,
                    ])->render();

                    $options = new Options();
                    $options->set('isRemoteEnabled', true);
                    $dompdf = new Dompdf($options);
                    $dompdf->loadHtml($html);
                    $dompdf->setPaper('A4', 'landscape');
                    $dompdf->render();

                    return response()->streamDownload(
                        fn () => print($dompdf->output()),
                        $filename
                    );
                }),
        ];
    }
}
