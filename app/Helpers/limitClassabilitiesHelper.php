<?php

namespace App\Helpers;

use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
class limitClassabilitiesHelper
{
    public static function limitClassability1(Get $get, Set $set) : int
    {
        $xp = $get('xp');
        $limit = match (true) {
            $xp >= 4 => 3,
            $xp >= 2 => 2,

            default => 1,
        };

        if (is_array($get('classability1')) && count($get('classability1')) > $limit) {
            $set('classability1', array_slice($get('classability1'), 0, $limit));
        }
        return $limit;
    }
    public static function limitclassability2(Get $get, Set $set) : int
    {
        $xp = $get('xp');
        $limit = match (true) {
            $xp >= 11 => 2,
            $xp >= 7 => 1,
            default => 0,
        };

        if (is_array($get('classability2')) && count($get('classability2')) > $limit) {
            $set('classability2', array_slice($get('classability2'), 0, $limit));

            // Warnung anzeigen
//            Notification::make()
//                ->title("Du darfst auf Stufe {$xp} maximal {$limit} Klassenfertigkeiten wählen.")
//                ->danger()
//                ->send();
        }
        return $limit;
    }
    public static function limitclassability3(Get $get, Set $set) : int
    {
        $xp = $get('xp');
        $limit = match (true) {
            $xp >= 22 => 2,
            $xp >= 16 => 1,
            default => 0,
        };

        if (is_array($get('classability3')) && count($get('classability3')) > $limit) {
            $set('classability3', array_slice($get('classability3'), 0, $limit));

            // Warnung anzeigen
//            Notification::make()
//                ->title("Du darfst auf Stufe {$xp} maximal {$limit} Klassenfertigkeiten wählen.")
//                ->danger()
//                ->send();
        }
        return $limit;
    }

}
