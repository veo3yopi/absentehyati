<?php

namespace App\Filament\Resources\AttendanceRecaps;

use App\Filament\Resources\AttendanceRecaps\Pages\CreateAttendanceRecap;
use App\Filament\Resources\AttendanceRecaps\Pages\EditAttendanceRecap;
use App\Filament\Resources\AttendanceRecaps\Pages\ListAttendanceRecaps;
use App\Filament\Resources\AttendanceRecaps\Schemas\AttendanceRecapForm;
use App\Filament\Resources\AttendanceRecaps\Tables\AttendanceRecapsTable;
use App\Models\AttendanceRecap;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AttendanceRecapResource extends Resource
{
    protected static ?string $model = AttendanceRecap::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'AttendanceRecap';

    public static function form(Schema $schema): Schema
    {
        return AttendanceRecapForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendanceRecapsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendanceRecaps::route('/'),
            'create' => CreateAttendanceRecap::route('/create'),
            'edit' => EditAttendanceRecap::route('/{record}/edit'),
        ];
    }
}
