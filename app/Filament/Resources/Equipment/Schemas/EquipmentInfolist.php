<?php

namespace App\Filament\Resources\Equipment\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EquipmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('name')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('quality')
                    ->placeholder('-'),
                TextEntry::make('item_type')
                    ->placeholder('-'),
                TextEntry::make('hwp')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('waffengattung')
                    ->placeholder('-'),
                TextEntry::make('attackvalue')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('trefferwuerfel')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('traglast')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('passive_verteidigung')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('schild_verteidigung')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('rs_schnitt')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('rs_stumpf')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('rs_stich')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('rs_elementar')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('kontrollwiderstand')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('rs_arcan')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('rs_chaos')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('rs_spirit')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('enchantment_qs')
                    ->placeholder('-'),
                TextEntry::make('character_id')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('equipped')
                    ->boolean(),
            ]);
    }
}
