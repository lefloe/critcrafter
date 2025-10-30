<?php

namespace App\Filament\Player\Resources\Equipment\Pages;

use App\Filament\Player\Resources\Equipment\EquipmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEquipment extends ListRecords
{
    protected static string $resource = EquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
