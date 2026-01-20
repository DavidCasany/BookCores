<?php

namespace App\Filament\Resources\Llibres\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LlibreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('titol')
                    ->required(),
                Textarea::make('descripcio')
                    ->columnSpanFull(),
                TextInput::make('genere')
                    ->required(),
                TextInput::make('preu')
                    ->required()
                    ->numeric(),
                TextInput::make('nota_promig')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('img_portada'),
                TextInput::make('img_hero'),
                TextInput::make('fitxer_pdf'),
                TextInput::make('autor_id')
                    ->required()
                    ->numeric(),
                TextInput::make('editorial_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
