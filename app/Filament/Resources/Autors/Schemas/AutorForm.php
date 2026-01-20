<?php

namespace App\Filament\Resources\Autors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AutorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->required(),
                Textarea::make('biografia')
                    ->columnSpanFull(),
                TextInput::make('user_id')
                    ->numeric(),
            ]);
    }
}
