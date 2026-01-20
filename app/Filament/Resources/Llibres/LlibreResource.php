<?php

namespace App\Filament\Resources\Llibres;

use App\Filament\Resources\Llibres\Pages\CreateLlibre;
use App\Filament\Resources\Llibres\Pages\EditLlibre;
use App\Filament\Resources\Llibres\Pages\ListLlibres;
use App\Filament\Resources\Llibres\Schemas\LlibreForm;
use App\Filament\Resources\Llibres\Tables\LlibresTable;
use App\Models\Llibre;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LlibreResource extends Resource
{
    protected static ?string $model = Llibre::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'titol';

    public static function form(Schema $schema): Schema
    {
        return LlibreForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LlibresTable::configure($table);
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
            'index' => ListLlibres::route('/'),
            'create' => CreateLlibre::route('/create'),
            'edit' => EditLlibre::route('/{record}/edit'),
        ];
    }
}
