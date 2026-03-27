<?php

namespace App\Filament\Player\Resources\Equipment\Pages;

use App\Filament\Player\Resources\Equipment\EquipmentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEquipment extends EditRecord
{
    protected static string $resource = EquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
    public function emptyForm(callable $set): void
    {
        $allFieldNames = array_keys($this->form->getRawState());
        // Felder, die NICHT geleert werden sollen
        $fieldsToKeep = [
            'id',
            'character_id',
            'item_type',
            'name',
            'description',
            'quality',
        ];

        foreach ($allFieldNames as $fieldName) {
            if (!in_array($fieldName, $fieldsToKeep)) {
                // Ruft die Setter-Funktion auf, um den Wert im Formularstatus auf null zu setzen.
                $set($fieldName, null);
            }
        }
    }
}
