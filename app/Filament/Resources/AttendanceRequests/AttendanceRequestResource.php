<?php

namespace App\Filament\Resources\AttendanceRequests;

use App\Filament\Resources\AttendanceRequests\Pages\CreateAttendanceRequest;
use App\Filament\Resources\AttendanceRequests\Pages\EditAttendanceRequest;
use App\Filament\Resources\AttendanceRequests\Pages\ListAttendanceRequests;
use App\Filament\Resources\AttendanceRequests\Pages\ViewAttendanceRequest;
use App\Filament\Resources\AttendanceRequests\Schemas\AttendanceRequestForm;
use App\Filament\Resources\AttendanceRequests\Tables\AttendanceRequestsTable;
use App\Models\AttendanceRequest;
use Illuminate\Database\Eloquent\Model;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AttendanceRequestResource extends Resource
{
    protected static ?string $model = AttendanceRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Pengajuan Absensi';

    protected static ?int $navigationSort = 30;

    protected static ?string $recordTitleAttribute = null;

    public static function form(Schema $schema): Schema
    {
        return AttendanceRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendanceRequestsTable::configure($table);
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
            'index' => ListAttendanceRequests::route('/'),
            'create' => CreateAttendanceRequest::route('/create'),
            'view' => ViewAttendanceRequest::route('/{record}'),
            'edit' => EditAttendanceRequest::route('/{record}/edit'),
        ];
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return $record->status === AttendanceRequest::STATUS_PENDING;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function getRecordTitle(?Model $record): string
    {
        if (! $record instanceof AttendanceRequest) {
            return '';
        }

        $startDate = $record->start_date?->format('d/m/Y') ?? $record->date?->format('d/m/Y') ?? '-';
        $teacherName = $record->teacher?->name ?? 'Guru';

        return $teacherName . ' - ' . $startDate;
    }
}
