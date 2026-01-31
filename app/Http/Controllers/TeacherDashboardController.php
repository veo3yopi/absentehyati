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
        $todayAttendance = Attendance::query()
            ->where('teacher_id', $teacher->id)
            ->whereDate('date', $today)
            ->first();

        $canCheckIn = ! $todayAttendance?->check_in_time;
        $canCheckOut = (bool) ($todayAttendance?->check_in_time && ! $todayAttendance?->check_out_time);

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
            'check_in_status' => ['required', Rule::in(['H', 'S', 'I', 'A'])],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        if (in_array($data['check_in_status'], ['S', 'I', 'A'], true) && empty($data['reason'])) {
            return back()->withErrors(['reason' => 'Alasan wajib diisi untuk status Sakit/Izin/Alfa.']);
        }

        $attendance = Attendance::query()
            ->where('teacher_id', $teacher->id)
            ->whereDate('date', $data['date'])
            ->first();

        if ($attendance?->check_in_time) {
            return back()->withErrors(['check_in_status' => 'Absen hari ini sudah tercatat.']);
        }

        $payload = [
            'teacher_id' => $teacher->id,
            'date' => $data['date'],
            'check_in_status' => $data['check_in_status'],
            'check_in_time' => now(),
            'note' => $data['reason'] ?? null,
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
            'check_out_status' => ['required', Rule::in(['H', 'S', 'I', 'A'])],
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

        $attendance->update([
            'check_out_status' => $data['check_out_status'],
            'check_out_time' => now(),
        ]);

        return redirect()->route('guru.dashboard')->with('status', 'Absen pulang berhasil dikirim.');
    }
}
