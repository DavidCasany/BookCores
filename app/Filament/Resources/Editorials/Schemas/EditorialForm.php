<?php

namespace App\Filament\Resources\Editorials\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EditorialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->required(),
                Textarea::make('descripcio')
                    ->columnSpanFull(),
            ]);
    }
}
