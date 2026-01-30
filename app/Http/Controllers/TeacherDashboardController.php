<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeacherDashboardController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user()->teacher;

        $today = now()->toDateString();
        $todayRequest = AttendanceRequest::query()
            ->where('teacher_id', $teacher->id)
            ->whereDate('date', $today)
            ->where('status', AttendanceRequest::STATUS_PENDING)
            ->first();

        $todayAttendance = Attendance::query()
            ->where('teacher_id', $teacher->id)
            ->whereDate('date', $today)
            ->first();

        $hasCheckIn = (bool) ($todayRequest?->check_in_submitted || $todayAttendance?->check_in_status);
        $canCheckIn = ! $hasCheckIn;

        $requests = AttendanceRequest::query()
            ->where('teacher_id', $teacher->id)
            ->orderByDesc('date')
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
            'todayRequest',
            'todayAttendance',
            'canCheckIn'
        ));
    }

    public function store(Request $request)
    {
        $teacher = $request->user()->teacher;

        $data = $request->validate([
            'date' => ['required', 'date', 'date_equals:today'],
            'check_in_status' => ['required', Rule::in(['H', 'S', 'I', 'A'])],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        if (in_array($data['check_in_status'], ['S', 'I', 'A'], true) && empty($data['reason'])) {
            return back()->withErrors(['reason' => 'Alasan wajib diisi untuk status Sakit/Izin/Alfa.']);
        }

        $existing = AttendanceRequest::query()
            ->where('teacher_id', $teacher->id)
            ->whereDate('date', $data['date'])
            ->where('status', AttendanceRequest::STATUS_PENDING)
            ->first();

        $todayAttendance = Attendance::query()
            ->where('teacher_id', $teacher->id)
            ->whereDate('date', $data['date'])
            ->first();

        $hasCheckIn = (bool) ($existing?->check_in_submitted || $todayAttendance?->check_in_status);

        if ($hasCheckIn) {
            return back()->withErrors(['check_in_status' => 'Absen hari ini sudah tercatat.']);
        }

        $payload = [
            'teacher_id' => $teacher->id,
            'date' => $data['date'],
            'check_in_status' => $data['check_in_status'],
            'check_out_status' => $existing?->check_out_status ?? 'H',
            'reason' => $data['reason'] ?? $existing?->reason,
            'status' => AttendanceRequest::STATUS_PENDING,
            'requested_by' => $request->user()->id,
        ];

        $payload['check_in_submitted'] = true;
        $payload['check_out_submitted'] = false;

        if ($existing) {
            $existing->update($payload);
        } else {
            AttendanceRequest::create($payload);
        }

        return redirect()->route('guru.dashboard')->with('status', 'Absensi hari ini berhasil dikirim.');
    }
}
