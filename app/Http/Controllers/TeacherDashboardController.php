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

        return view('guru.dashboard', compact('teacher', 'requests', 'attendances'));
    }

    public function store(Request $request)
    {
        $teacher = $request->user()->teacher;

        $data = $request->validate([
            'date' => ['required', 'date'],
            'check_in_status' => ['required', Rule::in(['H', 'S', 'I', 'A'])],
            'check_out_status' => ['required', Rule::in(['H', 'S', 'I', 'A'])],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $exists = AttendanceRequest::query()
            ->where('teacher_id', $teacher->id)
            ->whereDate('date', $data['date'])
            ->where('status', AttendanceRequest::STATUS_PENDING)
            ->exists();

        if ($exists) {
            return back()->withErrors(['date' => 'Sudah ada pengajuan pending untuk tanggal ini.']);
        }

        AttendanceRequest::create([
            'teacher_id' => $teacher->id,
            'date' => $data['date'],
            'check_in_status' => $data['check_in_status'],
            'check_out_status' => $data['check_out_status'],
            'reason' => $data['reason'] ?? null,
            'status' => AttendanceRequest::STATUS_PENDING,
            'requested_by' => $request->user()->id,
        ]);

        return redirect()->route('guru.dashboard')->with('status', 'Pengajuan absensi berhasil dikirim.');
    }
}
