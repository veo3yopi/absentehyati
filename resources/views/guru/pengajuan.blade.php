@extends('guru.layout')

@section('title', 'Pengajuan Absensi')

@section('container-width', '900px')

@section('page-style')
<style>
    h2 { font-size: 18px; margin: 0 0 12px; }
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
    .split { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

    @media (max-width: 900px) { .split { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
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
@endsection
