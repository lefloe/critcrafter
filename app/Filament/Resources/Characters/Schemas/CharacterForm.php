<?php

namespace App\Filament\Resources\Characters\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CharacterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('race')
                    ->required(),
                TextInput::make('wesen')
                    ->required(),
                TextInput::make('leiteigenschaft1')
                    ->required(),
                TextInput::make('leiteigenschaft2')
                    ->required(),
                TextInput::make('main_stat_value')
                    ->numeric(),
                Toggle::make('ko_toggle'),
                TextInput::make('archetype'),
                TextInput::make('rassenmerkmale'),
                TextInput::make('ko')
                    ->required()
                    ->numeric(),
                TextInput::make('st')
                    ->required()
                    ->numeric(),
                TextInput::make('ag')
                    ->required()
                    ->numeric(),
                TextInput::make('ge')
                    ->required()
                    ->numeric(),
                TextInput::make('we')
                    ->required()
                    ->numeric(),
                TextInput::make('in')
                    ->required()
                    ->numeric(),
                TextInput::make('mu')
                    ->required()
                    ->numeric(),
                TextInput::make('ch')
                    ->required()
                    ->numeric(),
                TextInput::make('skill_ko'),
                TextInput::make('skill_st'),
                TextInput::make('skill_ag'),
                TextInput::make('skill_ge'),
                TextInput::make('skill_we'),
                TextInput::make('skill_in'),
                TextInput::make('skill_mu'),
                TextInput::make('skill_ch'),
                TextInput::make('leps')
                    ->required()
                    ->numeric(),
                TextInput::make('tragkraft')
                    ->required()
                    ->numeric(),
                TextInput::make('geschwindigkeit')
                    ->required()
                    ->numeric(),
                TextInput::make('handwerksbonus')
                    ->required()
                    ->numeric(),
                TextInput::make('kontrollwiderstand')
                    ->required()
                    ->numeric(),
                TextInput::make('initiative')
                    ->required()
                    ->numeric(),
                TextInput::make('verteidigung')
                    ->required()
                    ->numeric(),
                TextInput::make('seelenpunkte')
                    ->required()
                    ->numeric(),
                TextInput::make('nw_gattung')
                    ->required(),
                TextInput::make('nw_quality')
                    ->required(),
                TextInput::make('nw_damage_type'),
                TextInput::make('nw_aw')
                    ->required()
                    ->numeric(),
                TextInput::make('nw_vw')
                    ->required()
                    ->numeric(),
                TextInput::make('nw_tw')
                    ->required()
                    ->numeric(),
                TextInput::make('xp')
                    ->required()
                    ->numeric(),
                TextInput::make('klassenfertigkeiten'),
                TextInput::make('klassenfertigkeiten2'),
                TextInput::make('klassenfertigkeiten3'),
                TextInput::make('handwerkskenntnisse'),
                TextInput::make('lore'),
            ]);
    }
}
