<?php

namespace App\Filament\Resources\Autors;

use App\Filament\Resources\Autors\Pages\CreateAutor;
use App\Filament\Resources\Autors\Pages\EditAutor;
use App\Filament\Resources\Autors\Pages\ListAutors;
use App\Filament\Resources\Autors\Schemas\AutorForm;
use App\Filament\Resources\Autors\Tables\AutorsTable;
use App\Models\Autor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AutorResource extends Resource
{
    protected static ?string $model = Autor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return AutorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AutorsTable::configure($table);
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
            'index' => ListAutors::route('/'),
            'create' => CreateAutor::route('/create'),
            'edit' => EditAutor::route('/{record}/edit'),
        ];
    }
}
