<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AttendanceRequests\AttendanceRequestResource;
use App\Models\AttendanceRequest;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class PendingAttendanceRequests extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Pengajuan Menunggu Verifikasi')
            ->query(
                AttendanceRequest::query()
                    ->where('status', AttendanceRequest::STATUS_PENDING)
                    ->latest()
            )
            ->columns([
                TextColumn::make('row_number')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('teacher.name')
                    ->label('Guru')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Jenis')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        AttendanceRequest::TYPE_SICK => 'Sakit',
                        AttendanceRequest::TYPE_LEAVE => 'Izin',
                        AttendanceRequest::TYPE_OUTSIDE => 'Dinas Luar',
                        AttendanceRequest::TYPE_WFH => 'WFH',
                        AttendanceRequest::TYPE_CUTI => 'Cuti',
                        default => $state ?? '-',
                    }),
                TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date(),
                TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date(),
                TextColumn::make('created_at')
                    ->label('Diajukan')
                    ->since(),
            ])
            ->recordUrl(fn (AttendanceRequest $record): string => AttendanceRequestResource::getUrl('view', ['record' => $record]))
            ->paginated([10])
            ->emptyStateHeading('Tidak ada pengajuan pending')
            ->emptyStateDescription('Semua pengajuan sudah diproses.')
            ->emptyStateIcon(Heroicon::OutlinedCheckCircle);
    }
}
