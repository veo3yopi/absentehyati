@extends('guru.layout')

@section('title', 'Riwayat Guru')

@section('container-width', '1100px')

@section('content')
<div class="card">
    <h2>Riwayat Absensi</h2>
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

<div class="card">
    <h2>Riwayat Pengajuan</h2>
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
