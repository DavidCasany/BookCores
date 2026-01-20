<?php

namespace App\Filament\Resources\Llibres\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LlibresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titol')
                    ->searchable(),
                TextColumn::make('genere')
                    ->searchable(),
                TextColumn::make('preu')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nota_promig')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('img_portada')
                    ->searchable(),
                TextColumn::make('img_hero')
                    ->searchable(),
                TextColumn::make('fitxer_pdf')
                    ->searchable(),
                TextColumn::make('autor_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('editorial_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
