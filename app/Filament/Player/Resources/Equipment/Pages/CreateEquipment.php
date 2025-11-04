<?php

namespace App\Filament\Player\Resources\Equipment\Pages;

use App\Filament\Player\Resources\Equipment\EquipmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEquipment extends CreateRecord
{
    protected static string $resource = EquipmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
