<?php

namespace App\Filament\Player\Resources\Characters\Pages;

use App\Filament\Player\Resources\Characters\CharacterResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateCharacter extends CreateRecord
{
    protected static string $resource = CharacterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('fillTestData')
                ->label('Testdaten ausfüllen')
                ->color('gray')
                ->action(function () {
                    $this->form->fill([
                        'name' => 'Testcharakter',
                        'race' => 'Ainu',
                        'xp' => 1,
                        'wesen' => 'Biest',
                        'leiteigenschaft1' => 'KO',
                        'leiteigenschaft2' => 'ST',
                        'ko' => 10,
                        'st' => 10,
                        'ag' => 10,
                        'ge' => 10,
                        'we' => 10,
                        'in' => 10,
                        'mu' => 10,
                        'ch' => 10,
                    ]);
                }),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        $data['tragkraft'] = $data['tragkraft'] ?? ($data['st'] ?? 0);
        $data['gs_leib'] = $data['gs_leib'] ?? (int) round(($data['ag'] ?? 0) / 2);
        $data['gs_seele'] = $data['gs_seele'] ?? 0;
        $data['handwerksbonus'] = $data['handwerksbonus'] ?? max(0, ($data['ge'] ?? 0) - 12);
        $data['kontrollwiderstand'] = $data['kontrollwiderstand'] ?? (($data['we'] ?? 0) - 12);
        $data['initiative'] = $data['initiative'] ?? (int) round(($data['in'] ?? 0) / 2);
        $data['verteidigung'] = $data['verteidigung'] ?? (($data['mu'] ?? 0) - 12);
        $data['seelenpunkte'] = $data['seelenpunkte'] ?? (($data['ch'] ?? 0) * 2 + ($data['bonus_sep'] ?? 0));
        $data['leps'] = $data['leps'] ?? (($data['ko'] ?? 0) * 2 + ($data['bonus_lep'] ?? 0));

        foreach (['ko', 'st', 'ag', 'ge', 'we', 'in', 'mu', 'ch'] as $attr) {
            $data["{$attr}_sum"] = $data["{$attr}_sum"] ?? ($data[$attr] ?? 0);
        }

        return $data;
    }
}
