<?php

namespace App\Filament\Player\Resources\Characters\Pages;

use App\Filament\Player\Resources\Characters\CharacterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCharacter extends CreateRecord
{
    protected static string $resource = CharacterResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
