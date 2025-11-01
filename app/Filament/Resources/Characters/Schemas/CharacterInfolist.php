<?php

namespace App\Filament\Resources\Characters\Schemas;

use App\Models\Character;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CharacterInfolist
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
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Character $record): bool => $record->trashed()),
                TextEntry::make('name'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('race'),
                TextEntry::make('wesen'),
                TextEntry::make('leiteigenschaft1'),
                TextEntry::make('leiteigenschaft2'),
                TextEntry::make('main_stat_value')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('ko_toggle')
                    ->boolean()
                    ->placeholder('-'),
                TextEntry::make('archetype')
                    ->placeholder('-'),
                TextEntry::make('ko')
                    ->numeric(),
                TextEntry::make('st')
                    ->numeric(),
                TextEntry::make('ag')
                    ->numeric(),
                TextEntry::make('ge')
                    ->numeric(),
                TextEntry::make('we')
                    ->numeric(),
                TextEntry::make('in')
                    ->numeric(),
                TextEntry::make('mu')
                    ->numeric(),
                TextEntry::make('ch')
                    ->numeric(),
                TextEntry::make('leps')
                    ->numeric(),
                TextEntry::make('tragkraft')
                    ->numeric(),
                TextEntry::make('geschwindigkeit')
                    ->numeric(),
                TextEntry::make('handwerksbonus')
                    ->numeric(),
                TextEntry::make('kontrollwiderstand')
                    ->numeric(),
                TextEntry::make('initiative')
                    ->numeric(),
                TextEntry::make('verteidigung')
                    ->numeric(),
                TextEntry::make('seelenpunkte')
                    ->numeric(),
                TextEntry::make('nw_gattung'),
                TextEntry::make('nw_quality'),
                TextEntry::make('nw_aw')
                    ->numeric(),
                TextEntry::make('nw_vw')
                    ->numeric(),
                TextEntry::make('nw_tw')
                    ->numeric(),
                TextEntry::make('xp')
                    ->numeric(),
            ]);
    }
}
