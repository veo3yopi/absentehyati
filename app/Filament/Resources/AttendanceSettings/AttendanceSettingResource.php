<?php

namespace App\Filament\Resources\AttendanceSettings;

use App\Filament\Resources\AttendanceSettings\Pages\CreateAttendanceSetting;
use App\Filament\Resources\AttendanceSettings\Pages\EditAttendanceSetting;
use App\Filament\Resources\AttendanceSettings\Pages\ListAttendanceSettings;
use App\Filament\Resources\AttendanceSettings\Schemas\AttendanceSettingForm;
use App\Filament\Resources\AttendanceSettings\Tables\AttendanceSettingsTable;
use App\Models\AttendanceSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AttendanceSettingResource extends Resource
{
    protected static ?string $model = AttendanceSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Aturan Absensi';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'start_time';

    public static function form(Schema $schema): Schema
    {
        return AttendanceSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendanceSettingsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendanceSettings::route('/'),
            'create' => CreateAttendanceSetting::route('/create'),
            'edit' => EditAttendanceSetting::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return 'Pengaturan';
    }

    public static function canCreate(): bool
    {
        return AttendanceSetting::query()->count() === 0;
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
