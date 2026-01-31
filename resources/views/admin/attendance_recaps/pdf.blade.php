<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Absensi</title>
    <style>
        @page { margin: 20px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #111827; }
        .header { margin-bottom: 12px; border-bottom: 1px solid #e5e7eb; padding-bottom: 8px; }
        .title { font-size: 16px; font-weight: 700; margin-bottom: 4px; }
        .meta { font-size: 11px; color: #4b5563; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 6px 6px; text-align: center; }
        th { background: #f3f4f6; font-weight: 700; }
        .left { text-align: left; }
        .small { font-size: 10px; color: #6b7280; }
        .summary { margin: 10px 0 14px; }
        .summary table { width: auto; border: none; }
        .summary td { border: none; padding: 2px 10px 2px 0; }
        .summary .label { color: #6b7280; }
        .highlight { background: #f9fafb; font-weight: 700; }
        .footer { margin-top: 14px; border-top: 1px solid #e5e7eb; padding-top: 8px; }
    </style>
</head>
<body>
    @php
        $typeLabel = $recap->type === 'monthly' ? 'Per Bulan' : 'Per Semester';
        $periodLabel = $recap->type === 'monthly'
            ? sprintf('%s %s', \Carbon\Carbon::createFromDate($recap->period_start->year, $recap->period_start->month, 1)->translatedFormat('F'), $recap->period_start->year)
            : $recap->period_start->format('d/m/Y') . ' - ' . $recap->period_end->format('d/m/Y');

        $totals = [
            'H' => 0, 'S' => 0, 'I' => 0, 'A' => 0, 'D' => 0, 'W' => 0, 'C' => 0,
        ];
        foreach ($rows as $row) {
            $totals['H'] += (int) ($row->in_h ?? 0);
            $totals['S'] += (int) ($row->in_s ?? 0);
            $totals['I'] += (int) ($row->in_i ?? 0);
            $totals['A'] += (int) ($row->in_a ?? 0);
            $totals['D'] += (int) ($row->in_d ?? 0);
            $totals['W'] += (int) ($row->in_w ?? 0);
            $totals['C'] += (int) ($row->in_c ?? 0);
        }
        $totalDaysAll = array_sum($totals);
    @endphp

    <div class="header">
        <div class="title">Rekap Absensi Guru</div>
        <div class="meta">
            {{ $school->school_name ?? 'Sekolah' }}
            @if(!empty($school?->address))
                &middot; {{ $school->address }}
            @endif
        </div>
        <div class="meta">Tipe: {{ $typeLabel }} &middot; Periode: {{ $periodLabel }}</div>
        <div class="meta">Tahun Ajaran: {{ $recap->academic_year ?? '-' }} &middot; Semester: {{ $recap->semester ?? '-' }}</div>
    </div>

    <div class="summary small">
        <table>
            <tr>
                <td class="label">Total Hari (Semua Guru)</td>
                <td>{{ $totalDaysAll }}</td>
                <td class="label">Hadir</td>
                <td>{{ $totals['H'] }}</td>
                <td class="label">Alpa</td>
                <td>{{ $totals['A'] }}</td>
                <td class="label">% Hadir</td>
                <td>{{ $totalDaysAll > 0 ? round(($totals['H'] / $totalDaysAll) * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td class="label">Sakit</td>
                <td>{{ $totals['S'] }}</td>
                <td class="label">Izin</td>
                <td>{{ $totals['I'] }}</td>
                <td class="label">Dinas</td>
                <td>{{ $totals['D'] }}</td>
                <td class="label">WFH</td>
                <td>{{ $totals['W'] }}</td>
                <td class="label">Cuti</td>
                <td>{{ $totals['C'] }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
        <tr>
            <th>No.</th>
            <th class="left">Guru</th>
            <th>H</th>
            <th>S</th>
            <th>I</th>
            <th>A</th>
            <th>D</th>
            <th>W</th>
            <th>C</th>
            <th>Total</th>
            <th>% Hadir</th>
        </tr>
        </thead>
        <tbody>
        @forelse($rows as $row)
            @php
                $total = (int) $row->in_h + (int) $row->in_s + (int) $row->in_i + (int) $row->in_a + (int) $row->in_d + (int) $row->in_w + (int) $row->in_c;
                $percent = $total > 0 ? round(($row->in_h / $total) * 100, 1) : 0;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="left">{{ $row->teacher?->name ?? '-' }}</td>
                <td>{{ $row->in_h }}</td>
                <td>{{ $row->in_s }}</td>
                <td>{{ $row->in_i }}</td>
                <td>{{ $row->in_a }}</td>
                <td>{{ $row->in_d }}</td>
                <td>{{ $row->in_w }}</td>
                <td>{{ $row->in_c }}</td>
                <td class="highlight">{{ $total }}</td>
                <td class="highlight">{{ $percent }}%</td>
            </tr>
        @empty
            <tr>
                <td class="left" colspan="11">Belum ada data rekap.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="footer small">
        Dicetak: {{ now()->format('d/m/Y H:i') }} WIB
        @if(!empty($recap->generator?->name))
            &middot; Oleh: {{ $recap->generator->name }}
        @endif
        &middot; Keterangan: H=Hadir, S=Sakit, I=Izin, A=Alpa, D=Dinas, W=WFH, C=Cuti
    </div>
</body>
</html>
