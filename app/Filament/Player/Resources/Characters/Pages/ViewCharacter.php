<?php

namespace App\Filament\Player\Resources\Characters\Pages;

use App\Filament\Player\Resources\Characters\CharacterResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCharacter extends ViewRecord
{
    protected static string $resource = CharacterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download_pdf')
                ->label('Charakterbogen PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->url(fn () => route('character.pdf', $this->record))
                ->openUrlInNewTab(),
            EditAction::make(),
        ];
    }
}
