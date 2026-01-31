<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
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

        .menu {
            display: flex;
            align-items: center;
            gap: 18px;
        }
        .menu a {
            text-decoration: none;
            color: #0f172a;
            font-weight: 600;
            padding: 6px 8px;
            border-radius: 8px;
            border: 1px solid transparent;
            transition: background 120ms ease, color 120ms ease;
        }
        .menu a.active {
            background: #eef2ff;
            color: #1d4ed8;
            border-color: #e0e7ff;
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
            width: 100%;
            flex: 1 0 auto;
            max-width: 1100px;
            margin: 24px auto;
            padding: 0 16px;
        }

        .card {
            background: var(--bg-1);
            padding: 18px;
            border-radius: var(--radius-1);
            box-shadow: var(--shadow-2);
            margin-bottom: 18px;
            border: 1px solid #eef2f7;
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

        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { border-bottom: 1px solid #eef2f7; padding: 10px 8px; text-align: left; }
        th { color: var(--muted); font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge { padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; display: inline-block; }
        .pending { background: #fef3c7; color: #92400e; }
        .approved { background: #dcfce7; color: #166534; }
        .rejected { background: #fee2e2; color: #991b1b; }

        .footer {
            margin-top: auto;
            padding: 18px 16px;
            border-top: 1px solid #eef2f7;
            color: var(--muted);
            font-size: 13px;
            text-align: center;
            background: #ffffff;
        }

        .mobile-bottom-nav { display: none; }
        .mobile-bottom-nav a { text-decoration: none; }
        .mobile-badge {
            position: absolute;
            top: -4px;
            right: -6px;
            background: #ef4444;
            color: #fff;
            font-size: 10px;
            padding: 1px 5px;
            border-radius: 999px;
            line-height: 1;
        }
        .icon-wrap { position: relative; }

        @media (max-width: 900px) {
            .menu { display: none; }
            .mobile-bottom-nav {
                display: flex;
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                background: #ffffff;
                border-top: 1px solid #e2e8f0;
                padding: 8px 6px;
                justify-content: space-around;
                z-index: 20;
            }
            .mobile-bottom-nav a {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 4px;
                font-size: 11px;
                color: #64748b;
            }
            .mobile-bottom-nav a.active { color: #2563eb; }
            .mobile-bottom-nav svg {
                width: 20px;
                height: 20px;
                stroke: currentColor;
                fill: none;
                stroke-width: 1.8;
            }
            body { padding-bottom: 72px; }
            .footer { display: none; }
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
    @yield('page-style')
</head>
<body>
<header>
    <div class="brand">
        <span class="brand-mark" aria-hidden="true"></span>
        <span class="brand-text">Absensi Guru</span>
    </div>
    <nav class="menu" aria-label="Menu utama">
        <a href="{{ route('guru.dashboard') }}" class="{{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('guru.absen.page') }}" class="{{ request()->routeIs('guru.absen.page') ? 'active' : '' }}">Absen</a>
        <a href="{{ route('guru.absen.request.page') }}" class="{{ request()->routeIs('guru.absen.request.page') ? 'active' : '' }}">
            Pengajuan
            @if (($pendingCount ?? 0) > 0)
                <span class="badge pending" style="margin-left:6px;">{{ $pendingCount > 9 ? '9+' : $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('guru.absen.history') }}" class="{{ request()->routeIs('guru.absen.history') ? 'active' : '' }}">Riwayat</a>
    </nav>
    <div class="actions">
        <span class="lang">ID</span>
        <form method="post" action="{{ route('guru.logout') }}">
            @csrf
            <button type="submit" class="btn secondary" style="background:#ef4444;">Keluar</button>
        </form>
    </div>
</header>

<nav class="mobile-bottom-nav" aria-label="Navigasi mobile">
    <a href="{{ route('guru.dashboard') }}" class="{{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
        <span class="icon-wrap">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 10.5L12 3l9 7.5v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/></svg>
        </span>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('guru.absen.page') }}" class="{{ request()->routeIs('guru.absen.page') ? 'active' : '' }}">
        <span class="icon-wrap">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4zm7 9v-1a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v1"/></svg>
        </span>
        <span>Absen</span>
    </a>
    <a href="{{ route('guru.absen.request.page') }}" class="{{ request()->routeIs('guru.absen.request.page') ? 'active' : '' }}">
        <span class="icon-wrap">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 7h8M8 11h8M8 15h5M6 3h12a2 2 0 0 1 2 2v14l-4-3H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/></svg>
            @if (($pendingCount ?? 0) > 0)
                <span class="mobile-badge">{{ $pendingCount > 9 ? '9+' : $pendingCount }}</span>
            @endif
        </span>
        <span>Pengajuan</span>
    </a>
    <a href="{{ route('guru.absen.history') }}" class="{{ request()->routeIs('guru.absen.history') ? 'active' : '' }}">
        <span class="icon-wrap">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 12a8 8 0 1 0 8-8M4 4v6h6M12 8v5l3 3"/></svg>
        </span>
        <span>Riwayat</span>
    </a>
</nav>

<div class="container" style="max-width:@yield('container-width', '1100px');">
    @yield('content')
</div>

<footer class="footer">
    <div>{{ $schoolSetting?->school_name ?? 'Sekolah' }}</div>
    <div>{{ $schoolSetting?->address ?? '' }}</div>
    <div>Tahun Ajaran {{ $schoolSetting?->academic_year ?? '-' }} • Semester {{ $schoolSetting?->semester ?? '-' }}</div>
</footer>

@yield('page-script')
</body>
</html>
