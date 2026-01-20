<?php

namespace App\Filament\Resources\Llibres\Pages;

use App\Filament\Resources\Llibres\LlibreResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLlibres extends ListRecords
{
    protected static string $resource = LlibreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
