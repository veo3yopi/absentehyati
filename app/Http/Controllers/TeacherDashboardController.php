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
        $hasCheckOut = (bool) ($todayRequest?->check_out_submitted || $todayAttendance?->check_out_status);
        $canCheckIn = ! $hasCheckIn;
        $canCheckOut = $hasCheckIn && ! $hasCheckOut;

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
            'canCheckIn',
            'canCheckOut'
        ));
    }

    public function store(Request $request)
    {
        $teacher = $request->user()->teacher;

        $data = $request->validate([
            'type' => ['required', Rule::in(['in', 'out'])],
            'date' => ['required', 'date', 'date_equals:today'],
            'check_in_status' => ['nullable', Rule::in(['H', 'S', 'I', 'A'])],
            'check_out_status' => ['nullable', Rule::in(['H', 'S', 'I', 'A'])],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($data['type'] === 'in' && empty($data['check_in_status'])) {
            return back()->withErrors(['check_in_status' => 'Status masuk wajib diisi.']);
        }
        if ($data['type'] === 'out' && empty($data['check_out_status'])) {
            return back()->withErrors(['check_out_status' => 'Status pulang wajib diisi.']);
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
        $hasCheckOut = (bool) ($existing?->check_out_submitted || $todayAttendance?->check_out_status);

        if ($data['type'] === 'out' && ! $hasCheckIn) {
            return back()->withErrors(['check_out_status' => 'Absen pulang hanya bisa setelah absen masuk.']);
        }
        if ($data['type'] === 'in' && $hasCheckIn) {
            return back()->withErrors(['check_in_status' => 'Absen masuk hari ini sudah tercatat.']);
        }
        if ($data['type'] === 'out' && $hasCheckOut) {
            return back()->withErrors(['check_out_status' => 'Absen pulang hari ini sudah tercatat.']);
        }

        $payload = [
            'teacher_id' => $teacher->id,
            'date' => $data['date'],
            'check_in_status' => $existing?->check_in_status ?? 'H',
            'check_out_status' => $existing?->check_out_status ?? 'H',
            'reason' => $data['reason'] ?? $existing?->reason,
            'status' => AttendanceRequest::STATUS_PENDING,
            'requested_by' => $request->user()->id,
        ];

        if ($data['type'] === 'in') {
            $payload['check_in_status'] = $data['check_in_status'];
            $payload['check_in_submitted'] = true;
        }
        if ($data['type'] === 'out') {
            $payload['check_out_status'] = $data['check_out_status'];
            $payload['check_out_submitted'] = true;
        }

        if ($existing) {
            $existing->update($payload);
        } else {
            AttendanceRequest::create($payload);
        }

        return redirect()->route('guru.dashboard')->with('status', 'Pengajuan absensi berhasil dikirim.');
    }
}
