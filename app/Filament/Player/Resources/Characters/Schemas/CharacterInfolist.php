<?php

namespace App\Filament\Player\Resources\Characters\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class CharacterInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(9)
                    ->columnSpanFull()
                    ->schema([
                        Tabs::make('Tabs')
                            ->columnSpan(5)
                            ->tabs([
                                Tabs\Tab::make('Grundwerte')
                                    ->schema([
                                        Grid::make(4)
                                            ->schema([
                                                TextEntry::make('name')
                                                    ->columnSpan(2)
                                                    ->label('Name'),
                                                TextEntry::make('race')
                                                    ->label('Rasse'),
                                                TextEntry::make('xp')
                                                    ->label('Erfahrungsgrad'),
                                            ]),
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('racial_traits')
                                                    ->label('Rassenmerkmale')
                                                    ->listWithLineBreaks(),
                                                TextEntry::make('archetype')
                                                    ->label('Archetyp'),
                                            ]),
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('wesen')
                                                    ->label('Wesen'),
                                                TextEntry::make('leiteigenschaft1')
                                                    ->label('Leiteigenschaft 1'),
                                            ]),
                                        TextEntry::make('description')
                                            ->label('Beschreibung')
                                            ->html()
                                            ->columnSpanFull(),
                                    ]),

                                Tabs\Tab::make('Eigenschaften')
                                    ->schema([
                                        Section::make('Eigenschaften')
                                            ->schema([
                                                Grid::make(8)
                                                    ->schema([
                                                        TextEntry::make('ko_sum')
                                                            ->label('KO'),
                                                        TextEntry::make('st_sum')
                                                            ->label('ST'),
                                                        TextEntry::make('ag_sum')
                                                            ->label('AG'),
                                                        TextEntry::make('ge_sum')
                                                            ->label('GE'),
                                                        TextEntry::make('we_sum')
                                                            ->label('WE'),
                                                        TextEntry::make('in_sum')
                                                            ->label('IN'),
                                                        TextEntry::make('mu_sum')
                                                            ->label('MU'),
                                                        TextEntry::make('ch_sum')
                                                            ->label('CH'),
                                                    ]),
                                            ]),
                                        Grid::make(4)
                                            ->schema([
                                                TextEntry::make('leps')
                                                    ->label('LeP'),
                                                TextEntry::make('seelenpunkte')
                                                    ->label('SeP'),
                                                TextEntry::make('tragkraft')
                                                    ->label('Tragkraft'),
                                                TextEntry::make('initiative')
                                                    ->label('Initiative'),
                                                TextEntry::make('verteidigung')
                                                    ->label('Verteidigung'),
                                                TextEntry::make('kontrollwiderstand')
                                                    ->label('Kontrollwiderstand'),
                                                TextEntry::make('handwerksbonus')
                                                    ->label('Handwerksbonus'),
                                                TextEntry::make('gs_leib')
                                                    ->label('GS Leib'),
                                            ]),
                                    ]),

                                Tabs\Tab::make('Fertigkeiten')
                                    ->schema([
                                        Fieldset::make('Klassenfertigkeiten')
                                            ->schema([
                                                TextEntry::make('classability1')
                                                    ->label('Klassenfertigkeiten 1')
                                                    ->listWithLineBreaks(),
                                                TextEntry::make('classability2')
                                                    ->label('Klassenfertigkeiten 2')
                                                    ->listWithLineBreaks(),
                                                TextEntry::make('classability3')
                                                    ->label('Klassenfertigkeiten 3')
                                                    ->listWithLineBreaks(),
                                            ]),
                                        Fieldset::make('Handwerk und Überlieferungen')
                                            ->schema([
                                                TextEntry::make('handwerkskenntnisse')
                                                    ->label('Handwerkskenntnisse')
                                                    ->listWithLineBreaks(),
                                                TextEntry::make('lore')
                                                    ->label('Überlieferungen')
                                                    ->listWithLineBreaks(),
                                            ]),
                                        Section::make('Fertigkeiten')
                                            ->schema([
                                                Grid::make(2)
                                                    ->schema([
                                                        TextEntry::make('skill_weapon')
                                                            ->label('Waffenfertigkeiten')
                                                            ->listWithLineBreaks(),
                                                        TextEntry::make('skill_aspect')
                                                            ->label('Aspektfertigkeiten')
                                                            ->listWithLineBreaks(),
                                                    ]),
                                            ]),
                                    ]),

                                Tabs\Tab::make('Ausrüstung')
                                    ->schema([
                                        Section::make('Natürliche Waffe')
                                            ->schema([
                                                Grid::make(4)
                                                    ->schema([
                                                        TextEntry::make('nw_quality')
                                                            ->label('Qualität'),
                                                        TextEntry::make('nw_aw')
                                                            ->label('AW'),
                                                        TextEntry::make('nw_vw')
                                                            ->label('VW'),
                                                        TextEntry::make('nw_tw')
                                                            ->label('TW'),
                                                    ]),
                                                Grid::make(2)
                                                    ->schema([
                                                        TextEntry::make('nw_gattung')
                                                            ->label('Gattung')
                                                            ->listWithLineBreaks(),
                                                        TextEntry::make('nw_damage_type')
                                                            ->label('Schadensart')
                                                            ->listWithLineBreaks(),
                                                    ]),
                                            ]),
                                    ]),
                            ]),

                        Tabs::make('Seite')
                            ->columnSpan(4)
                            ->tabs([
                                Tabs\Tab::make('Seite 1')
                                    ->schema([
                                        Fieldset::make('Leib')
                                            ->schema([
                                                Grid::make(4)
                                                    ->schema([
                                                        TextEntry::make('ko_sum')->label('KO'),
                                                        TextEntry::make('st_sum')->label('ST'),
                                                        TextEntry::make('ag_sum')->label('AG'),
                                                        TextEntry::make('ge_sum')->label('GE'),
                                                    ]),
                                                Grid::make(2)
                                                    ->schema([
                                                        TextEntry::make('leps')->label('LeP'),
                                                        TextEntry::make('tragkraft')->label('Tragkraft'),
                                                        TextEntry::make('gs_leib')->label('GS Leib'),
                                                        TextEntry::make('handwerksbonus')->label('Handwerksbonus'),
                                                    ]),
                                            ]),
                                        Fieldset::make('Seele')
                                            ->schema([
                                                Grid::make(4)
                                                    ->schema([
                                                        TextEntry::make('we_sum')->label('WE'),
                                                        TextEntry::make('in_sum')->label('IN'),
                                                        TextEntry::make('mu_sum')->label('MU'),
                                                        TextEntry::make('ch_sum')->label('CH'),
                                                    ]),
                                                Grid::make(2)
                                                    ->schema([
                                                        TextEntry::make('seelenpunkte')->label('SeP'),
                                                        TextEntry::make('kontrollwiderstand')->label('Kontrollwiderstand'),
                                                        TextEntry::make('initiative')->label('Initiative'),
                                                        TextEntry::make('verteidigung')->label('Verteidigung'),
                                                    ]),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
