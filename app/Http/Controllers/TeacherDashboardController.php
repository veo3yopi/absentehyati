<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\AttendanceRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeacherDashboardController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user()->teacher;

        $today = now()->toDateString();
        $todayAttendance = Attendance::query()
            ->where('teacher_id', $teacher->id)
            ->whereDate('date', $today)
            ->first();

        $blockedByAbsence = $todayAttendance
            && $todayAttendance->check_in_status !== 'H'
            && ! $todayAttendance->check_in_time;

        $canCheckIn = ! $todayAttendance?->check_in_time && ! $blockedByAbsence;
        $canCheckOut = (bool) ($todayAttendance?->check_in_time && ! $todayAttendance?->check_out_time);

        $requests = AttendanceRequest::query()
            ->where('teacher_id', $teacher->id)
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->limit(50)
            ->get();

        $attendances = Attendance::query()
            ->where('teacher_id', $teacher->id)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->limit(50)
            ->get();

        return view('guru.dashboard', compact(
            'teacher',
            'requests',
            'attendances',
            'todayAttendance',
            'canCheckIn',
            'canCheckOut'
        ));
    }

    public function storeCheckIn(Request $request)
    {
        $teacher = $request->user()->teacher;

        $data = $request->validate([
            'date' => ['required', 'date', 'date_equals:today'],
            'check_in_status' => ['required', Rule::in(['H'])],
        ]);

        $attendance = Attendance::query()
            ->where('teacher_id', $teacher->id)
            ->whereDate('date', $data['date'])
            ->first();

        if ($attendance?->check_in_time) {
            return back()->withErrors(['check_in_status' => 'Absen hari ini sudah tercatat.']);
        }

        $setting = AttendanceSetting::query()->first();
        $now = now();
        $lateMinutes = 0;
        $startTime = $setting?->start_time?->format('H:i') ?? '07:00';
        $tolerance = $setting?->late_tolerance_minutes ?? 15;
        $start = $now->copy()->setTimeFromTimeString($startTime);
        $diff = $now->diffInMinutes($start, false);
        if ($diff < 0) {
            $lateMinutes = abs($diff);
        }
        if ($lateMinutes <= $tolerance) {
            $lateMinutes = 0;
        }

        $payload = [
            'teacher_id' => $teacher->id,
            'date' => $data['date'],
            'check_in_status' => $data['check_in_status'],
            'check_in_time' => $now,
            'late_minutes' => $lateMinutes,
        ];

        if ($attendance) {
            $attendance->update($payload);
        } else {
            Attendance::create($payload);
        }

        return redirect()->route('guru.dashboard')->with('status', 'Absen masuk berhasil dikirim.');
    }

    public function storeCheckOut(Request $request)
    {
        $teacher = $request->user()->teacher;

        $data = $request->validate([
            'date' => ['required', 'date', 'date_equals:today'],
            'check_out_status' => ['required', Rule::in(['H'])],
        ]);

        $attendance = Attendance::query()
            ->where('teacher_id', $teacher->id)
            ->whereDate('date', $data['date'])
            ->first();

        if (! $attendance?->check_in_time) {
            return back()->withErrors(['check_out_status' => 'Absen masuk belum dilakukan hari ini.']);
        }

        if ($attendance->check_out_time) {
            return back()->withErrors(['check_out_status' => 'Absen pulang hari ini sudah tercatat.']);
        }

        $setting = AttendanceSetting::query()->first();
        $now = now();
        $earlyLeaveMinutes = 0;
        $endTime = $setting?->end_time?->format('H:i') ?? '16:00';
        $end = $now->copy()->setTimeFromTimeString($endTime);
        if ($now->lessThan($end)) {
            $earlyLeaveMinutes = $now->diffInMinutes($end);
        }

        $attendance->update([
            'check_out_status' => $data['check_out_status'],
            'check_out_time' => $now,
            'early_leave_minutes' => $earlyLeaveMinutes,
        ]);

        return redirect()->route('guru.dashboard')->with('status', 'Absen pulang berhasil dikirim.');
    }

    public function storeAbsenceRequest(Request $request)
    {
        $teacher = $request->user()->teacher;

        $data = $request->validate([
            'type' => ['required', Rule::in([
                AttendanceRequest::TYPE_SICK,
                AttendanceRequest::TYPE_LEAVE,
                AttendanceRequest::TYPE_OUTSIDE,
                AttendanceRequest::TYPE_WFH,
                AttendanceRequest::TYPE_CUTI,
            ])],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $start = $data['start_date'];
        $end = $data['end_date'];

        $overlapRequest = AttendanceRequest::query()
            ->where('teacher_id', $teacher->id)
            ->whereIn('status', [AttendanceRequest::STATUS_PENDING, AttendanceRequest::STATUS_APPROVED])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('start_date', '<=', $start)->where('end_date', '>=', $end);
                    });
            })
            ->exists();

        if ($overlapRequest) {
            return back()->withErrors(['start_date' => 'Sudah ada pengajuan pada rentang tanggal tersebut.']);
        }

        $overlapAttendance = Attendance::query()
            ->where('teacher_id', $teacher->id)
            ->whereBetween('date', [$start, $end])
            ->exists();

        if ($overlapAttendance) {
            return back()->withErrors(['start_date' => 'Sudah ada absensi pada rentang tanggal tersebut.']);
        }

        AttendanceRequest::create([
            'teacher_id' => $teacher->id,
            'type' => $data['type'],
            'date' => $start,
            'start_date' => $start,
            'end_date' => $end,
            'reason' => $data['reason'],
            'status' => AttendanceRequest::STATUS_PENDING,
            'requested_by' => $request->user()->id,
        ]);

        return redirect()->route('guru.dashboard')->with('status', 'Pengajuan berhasil dikirim dan menunggu verifikasi.');
    }
}
