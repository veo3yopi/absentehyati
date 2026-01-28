<?php

namespace App\Filament\Resources\SchoolSettings;

use App\Filament\Resources\SchoolSettings\Pages\CreateSchoolSetting;
use App\Filament\Resources\SchoolSettings\Pages\EditSchoolSetting;
use App\Filament\Resources\SchoolSettings\Pages\ListSchoolSettings;
use App\Filament\Resources\SchoolSettings\Schemas\SchoolSettingForm;
use App\Filament\Resources\SchoolSettings\Tables\SchoolSettingsTable;
use App\Models\SchoolSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SchoolSettingResource extends Resource
{
    protected static ?string $model = SchoolSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'SchoolSetting';

    public static function form(Schema $schema): Schema
    {
        return SchoolSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SchoolSettingsTable::configure($table);
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
            'index' => ListSchoolSettings::route('/'),
            'create' => CreateSchoolSetting::route('/create'),
            'edit' => EditSchoolSetting::route('/{record}/edit'),
        ];
    }
}
