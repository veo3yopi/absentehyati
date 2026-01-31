<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Guru</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap');

        :root {
            --bg-0: #f2f5fb;
            --bg-1: #ffffff;
            --ink-1: #0f172a;
            --ink-2: #334155;
            --muted: #64748b;
            --line: #e2e8f0;
            --brand-1: #1d4ed8;
            --brand-2: #0ea5e9;
            --success: #16a34a;
            --warning: #f59e0b;
            --danger: #ef4444;
            --shadow-1: 0 12px 30px rgba(15, 23, 42, 0.12);
            --shadow-2: 0 6px 16px rgba(15, 23, 42, 0.08);
            --radius-1: 14px;
            --radius-2: 22px;
        }

        * { box-sizing: border-box; }
        body {
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(1200px 400px at 15% -10%, rgba(14, 165, 233, 0.18), transparent 60%),
                radial-gradient(900px 360px at 90% 0%, rgba(29, 78, 216, 0.20), transparent 60%),
                var(--bg-0);
            color: var(--ink-1);
            margin: 0;
        }

        header {
            background: linear-gradient(120deg, #0f172a, #111827 60%, #1f2937);
            color: #fff;
            padding: 18px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-2);
        }

        header h1 {
            font-size: 22px;
            margin: 0;
            letter-spacing: -0.2px;
        }

        .container {
            max-width: 1100px;
            margin: 24px auto 48px;
            padding: 0 16px;
        }

        .card {
            background: var(--bg-1);
            padding: 18px;
            border-radius: var(--radius-1);
            box-shadow: var(--shadow-2);
            margin-bottom: 18px;
            border: 1px solid #eef2f7;
            animation: rise 420ms ease both;
        }

        @keyframes rise {
            from { transform: translateY(8px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        h2 {
            font-size: 18px;
            margin: 0 0 12px;
            color: var(--ink-1);
        }

        p, small { color: var(--muted); }

        label { display: block; margin: 10px 0 6px; font-size: 13px; color: var(--ink-2); }
        input, select, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--line);
            border-radius: 10px;
            font-size: 14px;
            background: #fff;
        }
        textarea { min-height: 86px; }

        .btn {
            margin-top: 12px;
            padding: 10px 16px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brand-1), var(--brand-2));
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 18px rgba(29, 78, 216, 0.25);
            transition: transform 140ms ease, box-shadow 140ms ease;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; box-shadow: none; }
        .btn.secondary { background: #0f172a; box-shadow: none; }

        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { border-bottom: 1px solid #eef2f7; padding: 10px 8px; text-align: left; }
        th { color: var(--muted); font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }

        .badge { padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; display: inline-block; }
        .pending { background: #fef3c7; color: #92400e; }
        .approved { background: #dcfce7; color: #166534; }
        .rejected { background: #fee2e2; color: #991b1b; }

        .error { color: #b91c1c; font-size: 13px; margin-top: 6px; }
        .status {
            color: #065f46;
            background: #d1fae5;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .grid { display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 16px; }
        .split { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        .hero {
            display: flex;
            flex-wrap: wrap;
            gap: 12px 20px;
            align-items: center;
            justify-content: space-between;
        }
        .hero h3 { margin: 0; font-size: 20px; }
        .pill {
            padding: 6px 12px;
            background: #e0f2fe;
            color: #075985;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }
        .status-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            font-size: 13px;
            color: var(--ink-2);
        }
        .status-chip {
            background: #f8fafc;
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 6px 10px;
            font-weight: 600;
        }
        .tabs {
            display: inline-flex;
            gap: 6px;
            background: #eef2ff;
            border: 1px solid #e0e7ff;
            padding: 6px;
            border-radius: 999px;
        }
        .tab {
            border: 0;
            background: transparent;
            color: #1e3a8a;
            font-weight: 700;
            padding: 8px 14px;
            border-radius: 999px;
            cursor: pointer;
        }
        .tab.active {
            background: #1d4ed8;
            color: #fff;
            box-shadow: 0 8px 14px rgba(29, 78, 216, 0.25);
        }
        .tab[aria-disabled="true"] {
            opacity: 0.45;
            cursor: not-allowed;
        }
        .hint {
            font-size: 12px;
            color: var(--muted);
            margin-top: 6px;
        }

        @media (max-width: 900px) {
            .grid { grid-template-columns: 1fr; }
            .split { grid-template-columns: 1fr; }
        }

        @media (max-width: 720px) {
            header { padding: 14px 16px; }
            .container { padding: 0 12px; }
            .btn { width: 100%; }
            table, thead, tbody, th, td, tr { display: block; }
            thead { display: none; }
            tbody tr {
                border: 1px solid #eef2f7;
                border-radius: 12px;
                margin-bottom: 12px;
                padding: 8px 10px;
                background: #fff;
            }
            td {
                border: 0;
                padding: 6px 0;
                display: flex;
                justify-content: space-between;
                gap: 12px;
            }
            td::before {
                content: attr(data-label);
                font-weight: 700;
                color: var(--muted);
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.4px;
            }
        }
    </style>
</head>
<body>
<header>
    <h1>Dashboard Guru</h1>
    <form method="post" action="{{ route('guru.logout') }}">
        @csrf
        <button type="submit" class="btn secondary" style="background:#ef4444;">Keluar</button>
    </form>
</header>

<div class="container">
    <div class="card">
        <div class="hero">
            <div>
                <h3>Halo, {{ $teacher->name }}</h3>
                <small>{{ now()->format('l, d F Y') }}</small>
            </div>
            <span class="pill">Absensi Hari Ini</span>
        </div>
        <div class="status-row" style="margin-top:10px;">
            <span class="status-chip">
                Masuk:
                @if ($todayAttendance?->check_in_time)
                    {{ $todayAttendance->check_in_time->format('H:i') }}
                @else
                    belum
                @endif
            </span>
            <span class="status-chip">
                Pulang:
                @if ($todayAttendance?->check_out_time)
                    {{ $todayAttendance->check_out_time->format('H:i') }}
                @else
                    belum
                @endif
            </span>
        </div>

        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif
        <div style="margin-top:12px;">
            <div class="tabs" role="tablist" aria-label="Tab Absensi">
                <button class="tab active" type="button" role="tab" aria-selected="true" data-mode="checkin">Absen Masuk</button>
                <button class="tab" type="button" role="tab" aria-selected="false" data-mode="checkout" @if (! $canCheckOut) aria-disabled="true" disabled @endif>Absen Pulang</button>
            </div>
            @if (! $canCheckOut)
                <div class="hint">Tab Absen Pulang aktif setelah absen masuk.</div>
            @endif
        </div>

        <form id="attendance-form" method="post" action="{{ route('guru.absen.checkin') }}" style="margin-top:14px;">
            @csrf
            <label>Tanggal</label>
            <input type="text" value="{{ now()->toDateString() }}" readonly>
            <input type="hidden" name="date" value="{{ now()->toDateString() }}">
            @error('date')<div class="error">{{ $message }}</div>@enderror

            <div id="checkin-fields">
                <label>Status Absen Masuk</label>
                <select id="checkin-status" name="check_in_status" {{ $canCheckIn ? '' : 'disabled' }}>
                    <option value="H" @selected(old('check_in_status', 'H') === 'H')>Hadir</option>
                </select>
                @error('check_in_status')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div id="checkout-fields" style="display:none;">
                <label>Status Absen Pulang</label>
                <select id="checkout-status" name="check_out_status" {{ $canCheckOut ? '' : 'disabled' }}>
                    <option value="H" @selected(old('check_out_status', 'H') === 'H')>Hadir</option>
                </select>
                @error('check_out_status')<div class="error">{{ $message }}</div>@enderror
            </div>

            <button id="attendance-submit" type="submit" class="btn" {{ $canCheckIn ? '' : 'disabled' }}>Simpan Absen Masuk</button>
            <p id="attendance-hint" style="font-size:12px; color:#6b7280; margin-top:10px;">
                @if ($todayAttendance?->check_in_time)
                    Absen masuk tercatat: {{ $todayAttendance->check_in_time->format('H:i') }}
                @elseif ($todayAttendance && $todayAttendance->check_in_status !== 'H')
                    Status hari ini: 
                    @switch($todayAttendance->check_in_status)
                        @case('S') Sakit @break
                        @case('I') Izin @break
                        @case('D') Dinas Luar @break
                        @case('W') WFH @break
                        @case('C') Cuti @break
                        @case('A') Alfa @break
                        @default {{ $todayAttendance->check_in_status }}
                    @endswitch
                @else
                    Belum absen masuk hari ini.
                @endif
            </p>
        </form>
    </div>

    <div class="card">
        <h2>Pengajuan Izin / Sakit / Dinas / WFH / Cuti</h2>
        <form method="post" action="{{ route('guru.absen.request') }}">
            @csrf
            <label>Jenis Pengajuan</label>
            <select name="type" required>
                @foreach (['S' => 'Sakit', 'I' => 'Izin', 'D' => 'Dinas Luar', 'W' => 'WFH', 'C' => 'Cuti'] as $k => $v)
                    <option value="{{ $k }}" @selected(old('type') === $k)>{{ $v }}</option>
                @endforeach
            </select>
            @error('type')<div class="error">{{ $message }}</div>@enderror

            <div class="split" style="margin-top:8px;">
                <div>
                    <label>Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" required>
                    @error('start_date')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label>Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" required>
                    @error('end_date')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            <label>Alasan</label>
            <textarea name="reason" required>{{ old('reason') }}</textarea>
            @error('reason')<div class="error">{{ $message }}</div>@enderror

            <button type="submit" class="btn">Kirim Pengajuan</button>
            <p class="hint">Pengajuan akan diverifikasi admin sebelum masuk rekap.</p>
        </form>
    </div>

    <div class="grid">
        <div class="card">
            <h2>Riwayat Pengajuan (Terbaru)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Jenis</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $req)
                        <tr>
                            <td data-label="Periode">
                                {{ $req->start_date?->format('Y-m-d') ?? $req->date->format('Y-m-d') }}
                                @if (($req->end_date ?? $req->date)->format('Y-m-d') !== ($req->start_date ?? $req->date)->format('Y-m-d'))
                                    - {{ $req->end_date?->format('Y-m-d') }}
                                @endif
                            </td>
                            <td data-label="Jenis">
                                @switch($req->type)
                                    @case('S') Sakit @break
                                    @case('I') Izin @break
                                    @case('D') Dinas Luar @break
                                    @case('W') WFH @break
                                    @case('C') Cuti @break
                                    @default {{ $req->type }}
                                @endswitch
                            </td>
                            <td data-label="Status">
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
                        <th>Masuk</th>
                        <th>Pulang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $att)
                        <tr>
                            <td data-label="Tanggal">{{ $att->date->format('Y-m-d') }}</td>
                            <td data-label="Masuk">
                                @switch($att->check_in_status)
                                    @case('H') Hadir @break
                                    @case('S') Sakit @break
                                    @case('I') Izin @break
                                    @case('A') Alfa @break
                                    @case('D') Dinas Luar @break
                                    @case('W') WFH @break
                                    @case('C') Cuti @break
                                    @default {{ $att->check_in_status }}
                                @endswitch
                                @if ($att->check_in_time)
                                    ({{ $att->check_in_time->format('H:i') }})
                                @endif
                            </td>
                            <td data-label="Pulang">
                                @if ($att->check_out_time)
                                    @switch($att->check_out_status)
                                        @case('H') Hadir @break
                                        @case('S') Sakit @break
                                        @case('I') Izin @break
                                        @case('A') Alfa @break
                                        @case('D') Dinas Luar @break
                                        @case('W') WFH @break
                                        @case('C') Cuti @break
                                        @default {{ $att->check_out_status }}
                                    @endswitch
                                    ({{ $att->check_out_time->format('H:i') }})
                                @else
                                    -
                                @endif
                            </td>
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
    (function () {
        const form = document.getElementById('attendance-form');
        const tabs = document.querySelectorAll('.tab');
        const checkinFields = document.getElementById('checkin-fields');
        const checkoutFields = document.getElementById('checkout-fields');
        const submitBtn = document.getElementById('attendance-submit');
        const hint = document.getElementById('attendance-hint');
        const checkinStatus = document.getElementById('checkin-status');
        const checkoutStatus = document.getElementById('checkout-status');
        const canCheckIn = {{ $canCheckIn ? 'true' : 'false' }};
        const canCheckOut = {{ $canCheckOut ? 'true' : 'false' }};

        function setMode(mode) {
            const isCheckIn = mode === 'checkin';
            checkinFields.style.display = isCheckIn ? 'block' : 'none';
            checkoutFields.style.display = isCheckIn ? 'none' : 'block';
            checkinStatus.required = isCheckIn;
            checkoutStatus.required = !isCheckIn;
            checkinStatus.disabled = isCheckIn ? !canCheckIn : true;
            checkoutStatus.disabled = isCheckIn ? true : !canCheckOut;
            form.action = isCheckIn
                ? "{{ route('guru.absen.checkin') }}"
                : "{{ route('guru.absen.checkout') }}";
            submitBtn.textContent = isCheckIn ? 'Simpan Absen Masuk' : 'Simpan Absen Pulang';
            submitBtn.disabled = isCheckIn ? !canCheckIn : !canCheckOut;
            hint.textContent = isCheckIn
                ? "{{ $todayAttendance?->check_in_time ? 'Absen masuk tercatat: ' . $todayAttendance->check_in_time->format('H:i') : (($todayAttendance && $todayAttendance->check_in_status !== 'H') ? 'Status hari ini: ' . match($todayAttendance->check_in_status) { 'S' => 'Sakit', 'I' => 'Izin', 'D' => 'Dinas Luar', 'W' => 'WFH', 'C' => 'Cuti', 'A' => 'Alfa', default => $todayAttendance->check_in_status } : 'Belum absen masuk hari ini.') }}"
                : "{{ $todayAttendance?->check_out_time ? 'Absen pulang tercatat: ' . $todayAttendance->check_out_time->format('H:i') : 'Absen pulang hanya bisa setelah absen masuk.' }}";
        }

        const defaultMode = (!canCheckIn && canCheckOut) ? 'checkout' : 'checkin';
        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                if (tab.getAttribute('aria-disabled') === 'true') return;
                tabs.forEach((t) => t.classList.remove('active'));
                tabs.forEach((t) => t.setAttribute('aria-selected', 'false'));
                tab.classList.add('active');
                tab.setAttribute('aria-selected', 'true');
                setMode(tab.dataset.mode);
            });
        });
        if (defaultMode === 'checkout') {
            const checkoutTab = document.querySelector('.tab[data-mode="checkout"]');
            if (checkoutTab && checkoutTab.getAttribute('aria-disabled') !== 'true') {
                tabs.forEach((t) => t.classList.remove('active'));
                tabs.forEach((t) => t.setAttribute('aria-selected', 'false'));
                checkoutTab.classList.add('active');
                checkoutTab.setAttribute('aria-selected', 'true');
                setMode('checkout');
            }
        } else {
            setMode('checkin');
        }
    })();
</script>
</body>
</html>
