<?php

namespace App\Filament\Resources\Equipment\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EquipmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name'),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('quality'),
                TextInput::make('item_type'),
                TextInput::make('hwp')
                    ->numeric(),
                TextInput::make('waffengattung'),
                TextInput::make('attackvalue')
                    ->numeric(),
                TextInput::make('damage_type'),
                TextInput::make('trefferwuerfel')
                    ->numeric(),
                TextInput::make('traglast')
                    ->numeric(),
                TextInput::make('passive_verteidigung')
                    ->numeric(),
                TextInput::make('schild_verteidigung')
                    ->numeric(),
                TextInput::make('rs_schnitt')
                    ->numeric(),
                TextInput::make('rs_stumpf')
                    ->numeric(),
                TextInput::make('rs_stich')
                    ->numeric(),
                TextInput::make('rs_elementar')
                    ->numeric(),
                TextInput::make('kontrollwiderstand')
                    ->numeric(),
                TextInput::make('rs_arcan')
                    ->numeric(),
                TextInput::make('rs_chaos')
                    ->numeric(),
                TextInput::make('rs_spirit')
                    ->numeric(),
                TextInput::make('enchantment'),
                TextInput::make('enchantment_qs'),
                TextInput::make('wp_erweiterungen'),
                TextInput::make('rs_erweiterungen'),
                TextInput::make('ts_erweiterungen'),
                TextInput::make('character_id')
                    ->numeric(),
                Toggle::make('equipped')
                    ->required(),
            ]);
    }
}
