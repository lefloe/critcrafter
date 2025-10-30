<?php

namespace App\Filament\Player\Resources\Characters\RelationManagers;

use App\Filament\Player\Resources\Equipment\EquipmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class EquipmentRelationManager extends RelationManager
{
    protected static string $relationship = 'Equipment';

    protected static ?string $relatedResource = EquipmentResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
