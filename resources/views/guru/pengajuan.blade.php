<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengajuan Absensi</title>
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
            --shadow-2: 0 6px 16px rgba(15, 23, 42, 0.08);
            --radius-1: 14px;
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background: #ffffff;
            color: var(--ink-1);
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #eef2f7;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        header h1 {
            font-size: 20px;
            margin: 0;
            letter-spacing: -0.2px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            letter-spacing: -0.2px;
        }
        .brand-mark {
            width: 26px;
            height: 26px;
            border-radius: 8px 4px 12px 4px;
            background: linear-gradient(135deg, #ef4444, #fb7185);
            transform: rotate(8deg);
            display: inline-block;
        }
        .brand-text {
            font-size: 18px;
            color: var(--ink-1);
        }
        .nav {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .nav a,
        .mobile-nav a {
            text-decoration: none;
            color: #0f172a;
            font-weight: 600;
            padding: 6px 8px;
            border-radius: 8px;
            border: 1px solid transparent;
            transition: background 120ms ease, color 120ms ease;
        }
        .nav a.active,
        .mobile-nav a.active {
            background: #eef2ff;
            color: #1d4ed8;
            border-color: #e0e7ff;
        }
        .nav-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #0f172a;
            cursor: pointer;
        }
        .nav-toggle span {
            display: block;
            width: 18px;
            height: 2px;
            background: #0f172a;
            margin: 3px 0;
        }
        .mobile-nav {
            display: none;
            flex-direction: column;
            gap: 8px;
            margin-top: 10px;
            padding: 10px 0;
        }
        .mobile-nav.open { display: flex; }
        .menu {
            display: flex;
            align-items: center;
            gap: 18px;
        }
        .menu a {
            color: #0f172a;
            font-weight: 600;
        }
        .menu a.active {
            color: #2563eb;
        }
        .actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .lang {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            color: #334155;
        }

        .container {
            max-width: 900px;
            margin: 24px auto 48px;
            padding: 0 16px;
            width: 100%;
            flex: 1 0 auto;
        }

        .card {
            background: var(--bg-1);
            padding: 18px;
            border-radius: var(--radius-1);
            box-shadow: var(--shadow-2);
            margin-bottom: 18px;
            border: 1px solid #eef2f7;
        }

        h2 { font-size: 18px; margin: 0 0 12px; }
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
        }
        .btn.secondary { background: #0f172a; box-shadow: none; }

        .status {
            color: #065f46;
            background: #d1fae5;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 12px;
            font-weight: 600;
        }
        .error { color: #b91c1c; font-size: 13px; margin-top: 6px; }
        .hint { font-size: 12px; color: var(--muted); margin-top: 6px; }

        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { border-bottom: 1px solid #eef2f7; padding: 10px 8px; text-align: left; }
        th { color: var(--muted); font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge { padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; display: inline-block; }
        .pending { background: #fef3c7; color: #92400e; }
        .approved { background: #dcfce7; color: #166534; }
        .rejected { background: #fee2e2; color: #991b1b; }
        .footer {
            margin-top: 32px;
            padding: 18px 16px;
            border-top: 1px solid #eef2f7;
            color: var(--muted);
            font-size: 13px;
            text-align: center;
            background: #ffffff;
            margin-top: auto;
        }

        .split { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        @media (max-width: 900px) {
            .split { grid-template-columns: 1fr; }
            .menu { display: none; }
            .nav-toggle { display: inline-flex; }
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
    <div class="brand">
        <span class="brand-mark" aria-hidden="true"></span>
        <span class="brand-text">Absensi Guru</span>
    </div>
    <nav class="menu" aria-label="Menu utama">
        <a href="{{ route('guru.dashboard') }}">Dashboard</a>
        <a href="{{ route('guru.absen.page') }}">Absen</a>
        <a href="{{ route('guru.absen.request.page') }}" class="active">Pengajuan</a>
        <a href="{{ route('guru.absen.history') }}">Riwayat</a>
    </nav>
    <div class="actions">
        <span class="lang">ID</span>
        <form method="post" action="{{ route('guru.logout') }}">
            @csrf
            <button type="submit" class="btn secondary" style="background:#ef4444;">Keluar</button>
        </form>
        <button class="nav-toggle" type="button" aria-label="Menu" aria-expanded="false" aria-controls="mobile-nav">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>
<nav id="mobile-nav" class="mobile-nav" aria-label="Menu mobile">
    <a href="{{ route('guru.dashboard') }}">Dashboard</a>
    <a href="{{ route('guru.absen.page') }}">Absen</a>
    <a href="{{ route('guru.absen.request.page') }}" class="active">Pengajuan</a>
    <a href="{{ route('guru.absen.history') }}">Riwayat</a>
</nav>

<div class="container">
    <div class="card">
        <h2>Form Pengajuan</h2>
        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif
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

    <div class="card">
        <h2>Pengajuan Terbaru</h2>
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
                            <span class="badge {{ $req->status }}">{{ ucfirst($req->status) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">Belum ada pengajuan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<footer class="footer">
    <div>{{ $schoolSetting?->school_name ?? 'Sekolah' }}</div>
    <div>{{ $schoolSetting?->address ?? '' }}</div>
    <div>Tahun Ajaran {{ $schoolSetting?->academic_year ?? '-' }} • Semester {{ $schoolSetting?->semester ?? '-' }}</div>
</footer>
<script>
    (function () {
        const toggle = document.querySelector('.nav-toggle');
        const mobileNav = document.getElementById('mobile-nav');
        if (!toggle || !mobileNav) return;
        toggle.addEventListener('click', () => {
            const isOpen = mobileNav.classList.toggle('open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    })();
</script>
</body>
</html>
