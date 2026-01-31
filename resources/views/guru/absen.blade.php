<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Absen Guru</title>
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
            margin-top: 10px;
        }
        .status-chip {
            background: #f8fafc;
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 6px 10px;
            font-weight: 600;
        }

        label { display: block; margin: 10px 0 6px; font-size: 13px; color: var(--ink-2); }
        input, select, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--line);
            border-radius: 10px;
            font-size: 14px;
            background: #fff;
        }

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
        .btn:disabled { opacity: 0.5; cursor: not-allowed; box-shadow: none; }

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

        @media (max-width: 900px) {
            .menu { display: none; }
            .nav-toggle { display: inline-flex; }
        }
        @media (max-width: 720px) {
            header { padding: 14px 16px; }
            .container { padding: 0 12px; }
            .btn { width: 100%; }
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
        <a href="{{ route('guru.absen.page') }}" class="active">Absen</a>
        <a href="{{ route('guru.absen.request.page') }}">Pengajuan</a>
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
    <a href="{{ route('guru.absen.page') }}" class="active">Absen</a>
    <a href="{{ route('guru.absen.request.page') }}">Pengajuan</a>
    <a href="{{ route('guru.absen.history') }}">Riwayat</a>
</nav>

<div class="container">
    <div class="card">
        <div class="hero">
            <div>
                <h3>Halo, {{ $teacher->name }}</h3>
                <small>{{ now()->format('l, d F Y') }}</small>
            </div>
            <span class="pill">Absensi Hari Ini</span>
        </div>
        <div class="status-row">
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
            <p id="attendance-hint" class="hint">
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
        if (toggle && mobileNav) {
            toggle.addEventListener('click', () => {
                const isOpen = mobileNav.classList.toggle('open');
                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        }
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
