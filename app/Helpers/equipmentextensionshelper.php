<?php

namespace App\Helpers;

use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class equipmentextensionshelper
{
    public static function getWpExtensions(Get $get, Set $set): array
    {
        return [
            'der einfachen Handhabung' => 'der einfachen Handhabung(1 HwP)',
            'der Einzigartigkeit' => 'der Einzigartigkeit(1 HwP)',
            'an der Kette' => 'an der Kette(2 HwP)',
            'der Kraftkontrolle' => 'der Kraftkontrolle(2 HwP)',
            'des Attentäters' => 'des Attentäters(2 HwP)',
            'der Flexibilität' => 'der Flexibilität (2 HwP)',
            'der Grausamkeit' => 'der Grausamkeit(3 HwP)',
            'der Härtung' => 'der Härtung(3 HwP)',
            'des Duells' => 'des Duells(3 HwP)',
            'des Tüftlers' => 'des Tüftlers(3 HwP)',
//          'mit Parierstange' => 'mit Parierstange(3 HwP)', <-wird über extra Feld geregelt
            'des Gemetzels' => 'des Gemetzels(3 HwP)',
            'der Präzision' => 'der Präzision(5 HW)',
            'der Zermürbung' => 'der Zermürbung(5 HwP)',
            'der Zielgenauigkeit' => 'der Zielgenauigkeit(5 HwP)',
            'der Brutalität' => 'der Brutalität(7 HwP)',
            'der Effizienz' => 'der Effizienz(7 HwP)',
            'der Kampfkunst' => 'der Kampfkunst(7 HwP)',
            'der Schlagkraft' => 'der Schlagkraft(7 HwP)',
            'der Revolution' => 'der Revolution(9 HwP)',
            'der Vorhut' => 'der Vorhut(9 HwP)',
            'der Wucht' => 'der Wucht(9 HwP)',
            'des Hinterhalts' => 'des Hinterhalts(9 HwP)',
            'des Jenseits' => 'des Jenseits(9 HwP)',
        ];

    }
}

