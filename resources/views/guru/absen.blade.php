@extends('guru.layout')

@section('title', 'Absen Guru')

@section('container-width', '900px')

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
    .hero-time {
        font-size: 13px;
        color: var(--muted);
        margin-top: 4px;
    }
    .clock {
        font-weight: 700;
        color: var(--ink-1);
        background: #f8fafc;
        border: 1px solid var(--line);
        padding: 4px 10px;
        border-radius: 999px;
        display: inline-block;
        font-variant-numeric: tabular-nums;
        margin-left: 6px;
    }
    .pill {
        padding: 6px 12px;
        background: #dcfce7;
        color: #166534;
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
    .tabs {
        display: inline-flex;
        gap: 6px;
        background: #ecfdf3;
        border: 1px solid #bbf7d0;
        padding: 6px;
        border-radius: 999px;
    }
    .tab {
        border: 0;
        background: transparent;
        color: #166534;
        font-weight: 700;
        padding: 8px 14px;
        border-radius: 999px;
        cursor: pointer;
    }
    .tab.active {
        background: #16a34a;
        color: #fff;
        box-shadow: 0 8px 14px rgba(22, 163, 74, 0.25);
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
</style>
@endsection

@section('content')
<div class="card">
    <div class="hero">
        <div>
            <h3>Halo, {{ $teacher->name }}</h3>
            <div class="hero-time">
                <span>{{ now()->format('l, d F Y') }}</span>
                <span class="clock" id="live-clock">--:--:--</span>
            </div>
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
@endsection

@section('page-script')
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
        const clock = document.getElementById('live-clock');

        function updateClock() {
            if (!clock) return;
            const now = new Date();
            const hh = String(now.getHours()).padStart(2, '0');
            const mm = String(now.getMinutes()).padStart(2, '0');
            const ss = String(now.getSeconds()).padStart(2, '0');
            clock.textContent = `${hh}:${mm}:${ss}`;
        }

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

        updateClock();
        setInterval(updateClock, 1000);
    })();
</script>
@endsection
