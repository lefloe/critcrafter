<?php

namespace App\Filament\Player\Resources\Characters\Pages;

use App\Filament\Player\Resources\Characters\CharacterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCharacters extends ListRecords
{
    protected static string $resource = CharacterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
