@extends('guru.layout')

@section('title', 'Dashboard Guru')

@section('container-width', '1100px')

@section('page-style')
<style>
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
    .quick-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-top: 14px;
    }
    .quick-card {
        border: 1px solid #eef2f7;
        border-radius: 14px;
        padding: 14px;
        background: #fff;
        box-shadow: var(--shadow-2);
        text-decoration: none;
        color: inherit;
    }
    .quick-card h4 { margin: 0 0 6px; font-size: 16px; }
    .quick-card p { margin: 0; font-size: 13px; color: var(--muted); }

    @media (max-width: 900px) {
        .quick-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="hero">
        <div>
            <h3>Halo, {{ $teacher->name }}</h3>
            <small>{{ now()->format('l, d F Y') }}</small>
        </div>
        <span class="pill">Ringkasan Hari Ini</span>
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
    <div class="quick-grid">
        <a class="quick-card" href="{{ route('guru.absen.page') }}">
            <h4>Absen Masuk/Pulang</h4>
            <p>Catat kehadiran hari ini dengan cepat.</p>
        </a>
        <a class="quick-card" href="{{ route('guru.absen.request.page') }}">
            <h4>Pengajuan Izin</h4>
            <p>Ajukan sakit, izin, dinas luar, WFH, atau cuti.</p>
        </a>
        <a class="quick-card" href="{{ route('guru.absen.history') }}">
            <h4>Riwayat</h4>
            <p>Lihat riwayat absensi dan pengajuan.</p>
        </a>
    </div>
</div>
@endsection
