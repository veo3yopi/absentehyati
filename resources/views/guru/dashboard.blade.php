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
        .tabs button { background: #e5e7eb; color: #111827; margin-top: 0; }
        .tabs button.active { background: #111827; color: #fff; }
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
        <h2>Absensi Hari Ini</h2>
        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        <div class="tabs" style="display:flex; gap:8px; margin-bottom:12px;">
            <button type="button" id="tab-in" class="active">Absen Masuk</button>
            <button type="button" id="tab-out" {{ $canCheckOut ? '' : 'disabled' }}>Absen Pulang</button>
        </div>

        <div id="panel-in">
            <form method="post" action="{{ route('guru.absen.store') }}">
                @csrf
                <input type="hidden" name="type" value="in">
                <label>Tanggal</label>
                <input type="date" name="date" value="{{ old('date', now()->toDateString()) }}" min="{{ now()->toDateString() }}" max="{{ now()->toDateString() }}" required>
                @error('date')<div class="error">{{ $message }}</div>@enderror

                <label>Status Masuk</label>
                <select name="check_in_status" required {{ $canCheckIn ? '' : 'disabled' }}>
                    @foreach (['H' => 'Hadir', 'S' => 'Sakit', 'I' => 'Izin', 'A' => 'Alfa'] as $k => $v)
                        <option value="{{ $k }}" @selected(old('check_in_status', 'H') === $k)>{{ $v }}</option>
                    @endforeach
                </select>
                @error('check_in_status')<div class="error">{{ $message }}</div>@enderror

                <label>Alasan (opsional)</label>
                <textarea name="reason" {{ $canCheckIn ? '' : 'disabled' }}>{{ old('reason') }}</textarea>

                <button type="submit" {{ $canCheckIn ? '' : 'disabled' }}>Kirim Pengajuan</button>
            </form>
        </div>

        <div id="panel-out" style="display:none;">
            <form method="post" action="{{ route('guru.absen.store') }}">
                @csrf
                <input type="hidden" name="type" value="out">
                <label>Tanggal</label>
                <input type="date" name="date" value="{{ old('date', now()->toDateString()) }}" min="{{ now()->toDateString() }}" max="{{ now()->toDateString() }}" required>
                @error('date')<div class="error">{{ $message }}</div>@enderror

                <label>Status Pulang</label>
                <select name="check_out_status" required {{ $canCheckOut ? '' : 'disabled' }}>
                    @foreach (['H' => 'Hadir', 'S' => 'Sakit', 'I' => 'Izin', 'A' => 'Alfa'] as $k => $v)
                        <option value="{{ $k }}" @selected(old('check_out_status', 'H') === $k)>{{ $v }}</option>
                    @endforeach
                </select>
                @error('check_out_status')<div class="error">{{ $message }}</div>@enderror

                <label>Alasan (opsional)</label>
                <textarea name="reason" {{ $canCheckOut ? '' : 'disabled' }}>{{ old('reason') }}</textarea>

                <button type="submit" {{ $canCheckOut ? '' : 'disabled' }}>Kirim Pengajuan</button>
            </form>
        </div>

        <p style="font-size:12px; color:#6b7280; margin-top:10px;">
            Absen pulang hanya bisa setelah absen masuk hari ini.
        </p>
    </div>

    <div class="grid">
        <div class="card">
            <h2>Riwayat Pengajuan (Terbaru)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $req)
                        <tr>
                            <td>{{ $req->date->format('Y-m-d') }}</td>
                            <td>{{ $req->check_in_status }}</td>
                            <td>{{ $req->check_out_status }}</td>
                            <td>
                                <span class="badge {{ $req->status }}">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4">Belum ada pengajuan.</td></tr>
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
                        <th>Masuk</th>
                        <th>Pulang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $att)
                        <tr>
                            <td>{{ $att->date->format('Y-m-d') }}</td>
                            <td>{{ $att->check_in_status }}</td>
                            <td>{{ $att->check_out_status }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">Belum ada data absensi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    const tabIn = document.getElementById('tab-in');
    const tabOut = document.getElementById('tab-out');
    const panelIn = document.getElementById('panel-in');
    const panelOut = document.getElementById('panel-out');

    tabIn?.addEventListener('click', () => {
        tabIn.classList.add('active');
        tabOut.classList.remove('active');
        panelIn.style.display = 'block';
        panelOut.style.display = 'none';
    });

    tabOut?.addEventListener('click', () => {
        if (tabOut.disabled) return;
        tabOut.classList.add('active');
        tabIn.classList.remove('active');
        panelOut.style.display = 'block';
        panelIn.style.display = 'none';
    });
</script>
</body>
</html>
