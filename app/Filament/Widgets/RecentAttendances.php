<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentAttendances extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Absensi Terbaru')
            ->query(
                Attendance::query()
                    ->with('teacher')
                    ->latest('date')
                    ->latest('updated_at')
            )
            ->columns([
                TextColumn::make('row_number')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('teacher.name')
                    ->label('Guru')
                    ->searchable(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date(),
                TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function (Attendance $record): string {
                        $status = $this->resolveFinalStatus($record->check_in_status, $record->check_out_status);

                        return match ($status) {
                            'H' => 'Hadir',
                            'S' => 'Sakit',
                            'I' => 'Izin',
                            'D' => 'Dinas Luar',
                            'W' => 'WFH',
                            'C' => 'Cuti',
                            'A' => 'Alpa',
                            default => $status,
                        };
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Hadir' => 'success',
                        'Sakit', 'Izin', 'Dinas Luar', 'WFH', 'Cuti' => 'warning',
                        'Alpa' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('check_in_time')
                    ->label('Masuk')
                    ->dateTime('H:i')
                    ->placeholder('-'),
                TextColumn::make('check_out_time')
                    ->label('Pulang')
                    ->dateTime('H:i')
                    ->placeholder('-'),
            ])
            ->paginated([10])
            ->emptyStateHeading('Belum ada data absensi')
            ->emptyStateDescription('Data absensi akan muncul setelah guru melakukan absen.')
            ->emptyStateIcon(Heroicon::OutlinedClipboardDocumentCheck);
    }

    private function resolveFinalStatus(?string $checkIn, ?string $checkOut): string
    {
        foreach ([$checkIn, $checkOut] as $code) {
            if (in_array($code, ['S', 'I', 'D', 'W', 'C', 'A'], true)) {
                return $code;
            }
        }

        if ($checkIn === 'H' || $checkOut === 'H') {
            return 'H';
        }

        return 'A';
    }
}
