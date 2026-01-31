<?php

namespace App\Filament\Resources\AttendanceRecaps;

use App\Filament\Resources\AttendanceRecaps\Pages\ListAttendanceRecaps;
use App\Models\AttendanceRecap;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

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
            ->columns([
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
            ->recordActions([])
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
