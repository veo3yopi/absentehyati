<?php

namespace App\Filament\Resources\AttendanceRecaps;

use App\Filament\Resources\AttendanceRecaps\Pages\ListAttendanceRecaps;
use App\Models\AttendanceRecap;
use App\Models\SchoolSetting;
use BackedEnum;
use Dompdf\Dompdf;
use Dompdf\Options;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Support\Str;

class AttendanceRecapResource extends Resource
{
    protected static ?string $model = AttendanceRecap::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Rekap Absensi';

    protected static ?int $navigationSort = 40;

    protected static ?string $recordTitleAttribute = 'academic_year';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('generated_at', 'desc')
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('row_number')
                    ->label('No.')
                    ->rowIndex(),
                \Filament\Tables\Columns\TextColumn::make('type'),
                \Filament\Tables\Columns\TextColumn::make('period_start')->date(),
                \Filament\Tables\Columns\TextColumn::make('period_end')->date(),
                \Filament\Tables\Columns\TextColumn::make('month')->numeric(),
                \Filament\Tables\Columns\TextColumn::make('academic_year'),
                \Filament\Tables\Columns\TextColumn::make('semester'),
                \Filament\Tables\Columns\TextColumn::make('generated_by')->numeric(),
                \Filament\Tables\Columns\TextColumn::make('generated_at')->dateTime(),
            ])
            ->filters([])
            ->recordActions([
                Action::make('exportPdf')
                    ->label('Export PDF')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->action(function (AttendanceRecap $record) {
                        $record->load(['rows.teacher', 'generator']);
                        $setting = SchoolSetting::query()->first();

                        $fileBase = 'rekap-absensi-' . ($record->academic_year ?? 'periode') . '-' . ($record->semester ?? '') . '-' . now()->format('Ymd-His');
                        $filename = Str::slug($fileBase, '-') . '.pdf';

                        $html = view('admin.attendance_recaps.pdf', [
                            'recap' => $record,
                            'rows' => $record->rows,
                            'school' => $setting,
                        ])->render();

                        $options = new Options();
                        $options->set('isRemoteEnabled', true);
                        $dompdf = new Dompdf($options);
                        $dompdf->loadHtml($html);
                        $dompdf->setPaper('A4', 'landscape');
                        $dompdf->render();

                        return response()->streamDownload(
                            fn () => print($dompdf->output()),
                            $filename
                        );
                    }),
            ])
            ->toolbarActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return 'Absensi';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendanceRecaps::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }
}
