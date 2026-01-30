<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Guru</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f6f7fb; margin: 0; }
        header { background: #111827; color: #fff; padding: 16px 24px; display:flex; justify-content:space-between; align-items:center; }
        .container { max-width: 1100px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; padding: 16px; border-radius: 10px; box-shadow: 0 6px 18px rgba(0,0,0,.06); margin-bottom: 20px; }
        h1 { font-size: 20px; margin: 0; }
        h2 { font-size: 16px; margin: 0 0 12px; }
        label { display:block; margin: 10px 0 6px; font-size: 14px; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #d7dbe6; border-radius: 6px; }
        textarea { min-height: 80px; }
        button { margin-top: 12px; padding: 10px 16px; border: 0; border-radius: 6px; background: #1f6feb; color: #fff; font-weight: 600; cursor: pointer; }
        button:disabled { opacity: 0.5; cursor: not-allowed; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { border-bottom: 1px solid #eee; padding: 8px; text-align: left; }
        .badge { padding: 2px 8px; border-radius: 999px; font-size: 12px; }
        .pending { background: #fef3c7; }
        .approved { background: #d1fae5; }
        .rejected { background: #fee2e2; }
        .error { color: #b91c1c; font-size: 13px; margin-top: 6px; }
        .status { color: #065f46; background: #d1fae5; padding: 8px 12px; border-radius: 6px; margin-bottom: 12px; }
        .grid { display:grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 900px) { .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<header>
    <h1>Dashboard Guru</h1>
    <form method="post" action="{{ route('guru.logout') }}">
        @csrf
        <button type="submit" style="background:#ef4444;">Keluar</button>
    </form>
</header>

<div class="container">
    <div class="card">
        <strong>Halo, {{ $teacher->name }}</strong>
    </div>

    <div class="card">
        <h2>Absensi Hari Ini (Sekali Saja)</h2>
        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        <form method="post" action="{{ route('guru.absen.store') }}">
            @csrf
            <label>Tanggal</label>
            <input type="text" value="{{ now()->toDateString() }}" readonly>
            <input type="hidden" name="date" value="{{ now()->toDateString() }}">
            @error('date')<div class="error">{{ $message }}</div>@enderror

            <label>Status Kehadiran</label>
            <select name="check_in_status" required {{ $canCheckIn ? '' : 'disabled' }}>
                @foreach (['H' => 'Hadir', 'S' => 'Sakit', 'I' => 'Izin', 'A' => 'Alfa'] as $k => $v)
                    <option value="{{ $k }}" @selected(old('check_in_status', 'H') === $k)>{{ $v }}</option>
                @endforeach
            </select>
            @error('check_in_status')<div class="error">{{ $message }}</div>@enderror

            <label>Alasan (wajib untuk S/I/A)</label>
            <textarea name="reason" {{ $canCheckIn ? '' : 'disabled' }}>{{ old('reason') }}</textarea>
            @error('reason')<div class="error">{{ $message }}</div>@enderror

            <button type="submit" {{ $canCheckIn ? '' : 'disabled' }}>Absen Sekarang</button>
        </form>

        <p style="font-size:12px; color:#6b7280; margin-top:10px;">
            Absensi hanya sekali per hari. Absen pulang tidak diperlukan.
        </p>
    </div>

    <div class="grid">
        <div class="card">
            <h2>Riwayat Pengajuan (Terbaru)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Status Pengajuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $req)
                        <tr>
                            <td>{{ $req->date->format('Y-m-d') }}</td>
                            <td>
                                @switch($req->check_in_status)
                                    @case('H') Hadir @break
                                    @case('S') Sakit @break
                                    @case('I') Izin @break
                                    @case('A') Alfa @break
                                    @default {{ $req->check_in_status }}
                                @endswitch
                            </td>
                            <td>
                                <span class="badge {{ $req->status }}">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3">Belum ada pengajuan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>Riwayat Absensi Final (Terbaru)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $att)
                        <tr>
                            <td>{{ $att->date->format('Y-m-d') }}</td>
                            <td>
                                @switch($att->check_in_status)
                                    @case('H') Hadir @break
                                    @case('S') Sakit @break
                                    @case('I') Izin @break
                                    @case('A') Alfa @break
                                    @default {{ $att->check_in_status }}
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2">Belum ada data absensi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
