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
        // 1. Holen Sie alle Feld-Namen, die aktuell im State gespeichert sind
        $allFieldNames = array_keys($this->form->getRawState());

        // 2. Definieren Sie alle Felder, die NICHT geleert werden sollen (z.B. Item-Basisdaten)
        $fieldsToKeep = [
            // Diese Felder bleiben erhalten, da sie für alle Item-Typen gelten
            'id',
            'character_id',
            'item_type',
            'name',
            'description',
            'quality',
        ];

        // 3. Iterieren Sie durch alle Felder und setzen Sie nicht-generische Felder auf null
        foreach ($allFieldNames as $fieldName) {
            if (!in_array($fieldName, $fieldsToKeep)) {
                // Ruft die Setter-Funktion auf, um den Wert im Formularstatus auf null zu setzen.
                $set($fieldName, null);
            }
        }
    }
}
