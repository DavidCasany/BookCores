<?php

namespace App\Filament\Resources\Llibres\Pages;

use App\Filament\Resources\Llibres\LlibreResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLlibre extends EditRecord
{
    protected static string $resource = LlibreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
