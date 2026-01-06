<?php

namespace App\Filament\Player\Resources\Equipment\Schemas;

use App\Helpers\equipmentextensionshelper;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class EquipmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('allgemein')
                    ->description('Art der Ausrüstung wählen')
                    ->schema([
                    Select::make('character_id')
                        ->label('Besitzer')
                        ->relationship('character', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('description')
                                    ->label('Beschreibung')
                                    ->maxLength(800),
                                Select::make('quality')
                                    ->required()
                                    ->options(self::getQS())
                                    ->afterStateUpdated(function ($livewire, Get $get, $set) {
                                        $livewire->emptyForm($set);
                                        self::setWeaponStats($get, $set);
                                        self::setArmorStats($get, $set);
                                        self::setCharmStats($get, $set);
                                        self::setShieldStats($get, $set);
                                    })
                                    ->live(),
                                Select::make('item_type')
                                    ->label('Ausrüstungsart')
                                    ->required()
                                    ->options([
                                        'Material' => 'Material',
                                        'Waffe' => 'Waffe',
                                        'Rüstung' => 'Rüstung',
                                        'Talisman' => 'Talisman',
                                        'Schild' => 'Schild',
                                        'Werkzeug' => 'Werkzeug',
                                        'Schmuckstück' => 'Schmuckstück',
                                        'Handelsware' => 'Handelsware',
                                        'Nahrungsmittel' => 'Nahrungsmittel',
                                        'Paraphernalia' => 'Paraphernalia',
                                        'Leib' => 'Leib',
                                        'Anwendung' => 'Anwendung',
                                        'Rucksack' => 'Rucksack',
                                        'sonstiges' => 'sonstiges',
                                    ])
                                    ->live()
                                    ->afterStateUpdated(function ($livewire, $state, Get $get,Set $set) {
                                        $livewire->emptyForm($set);
                                        switch ($state) {
                                            case 'Waffe':
                                                self::setWeaponStats($get, $set);
                                                break;
                                            case 'Rüstung':
                                                self::setArmorStats($get, $set);
                                                break;
                                            case 'Talisman':
                                                self::setCharmStats($get, $set);
                                                break;
                                            case 'Schild':
                                                self::setShieldStats($get, $set);
                                                break;
                                        }
                                    }),
                            ])
                    ]),
                Section::make('weapon')
                    ->label('Waffe')
                    ->description('Werte der Waffe wählen')
                    ->visible(fn (callable $get) => $get('item_type') == 'Waffe')
                    ->schema([
                        Grid::make(3)
                        ->schema([
                            Textinput::make('hwp')
                                ->label('Handwerkspunkte')
                                ->numeric()
                                ->hint(function ($state, Get $get, Set $set) {
                                    return $state - self::setWeaponHwp($get, $set);
                                })
                                ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                    return $state - self::setWeaponHwp($get, $set);
                                })
                                ->live(),
                            Textinput::make('attackvalue')
                                ->label('Angriffswert')
                                ->numeric()
                                ->step(1),
                            Textinput::make('traglast')
                                ->label('Traglast')
                                ->numeric()
                                ->step(1)
                                ->minvalue(1)
                                ->maxvalue(9),
                            Select::make('waffenführung')
                                ->label('Waffenführung')
                                ->live()
                                ->options([
                                    'Zweihändig' => 'Zweihändig',
                                    'Einhändig' => 'Einhändig',
                                    'Beidhändig' => 'Beidhändig',
                                    'Freihändig' => 'Freihändig',
                                    'mit Schild' => 'mit Schild',
                                ])
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    self::setWeaponStats($get, $set);
                                })
                                ->live(),
                            Textinput::make('count_dice')
                                ->label('Trefferwürfel')
                                ->numeric()
                                ->step(1)
                                ->minvalue(1)
                                ->maxvalue(9),
                            TextInput::make('tw')
                                ->label('Würfel'),
                            Radio::make('waffengattung')
                                ->options([
                                    'Nahkampfwaffe' => 'Nahkampfwaffe',
                                    'Fernkampfwaffe' => 'Fernkampfwaffe',
                                ]),
                            Textinput::make('wp_vw')
                                ->label('VW 3HwP pro 2 VW')
                                ->numeric()
                                ->step(2)
                                ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                    self::setWeaponHwp($get, $set);
                                })
                                ->live(),
                            Select::make('damage_type')
                                ->Label('Leiteigenschaft (2te mit "der Flexibilität")')
                                ->multiple()
                                ->options([
                                    'stumpf' => 'ST (Stumpf)',
                                    'schnitt' => 'AG (Schnitt)',
                                    'stich' => 'GE (Stich)',
                                    'arkan' => 'WE (Arkan)',
                                    'elementar' => 'IN (Elementar)',
                                    'chaos' => 'MU (Chaos)',
                                    'spirituell' => 'CH (Spirituell)',
                                ]),
                            ]),
                        Select::make('wp_erweiterungen')
                            ->label('Erweiterungen')
                            ->multiple()
                            ->options(fn (Get $get, Set $set) =>
                                equipmentextensionshelper::getWpExtensions($get, $set)
                            )
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::setWeaponStats($get, $set);
                            })
                            ->live(),
                    ]),
                Section::make('armor')
                    ->description('Werte der Rüstung wählen')
                    ->visible(fn (callable $get) => $get('item_type') == 'Rüstung')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Textinput::make('hwp')
                                    ->label('Handwerkspunkte')
                                    ->numeric()->hint(function ($state, Get $get, Set $set) {
                                        return $state - self::setArmorHwp($get, $set);
                                    })
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        return $state - self::setArmorHwp($get, $set);
                                    })
                                    ->live(),
                                Textinput::make('pVW')
                                    ->label('passive Verteidigung')
                                    ->numeric(),
                                Textinput::make('traglast')
                                    ->label('Traglast')
                                    ->numeric(),
                            ]),
                        Grid::make(4)
                            ->schema([
                                Textinput::make('armor_schnitt')
                                    ->label('RS Schnitt')
                                    ->numeric()
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        return $state - self::setArmorHwp($get, $set);
                                    })
                                    ->live(),
                        Textinput::make('armor_stumpf')
                                    ->label('RS Stumpf')
                                    ->numeric()
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        return $state - self::setArmorHwp($get, $set);
                                    })
                                    ->live(),
                                Textinput::make('armor_stich')
                                    ->label('RS Stich')
                                    ->numeric()
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        return $state - self::setArmorHwp($get, $set);
                                    })
                                    ->live(),
                                Textinput::make('armor_elementar')
                                    ->label('RS Elementar')
                                    ->numeric()
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        return $state - self::setArmorHwp($get, $set);
                                    })
                                    ->live(),
                            ]),
                            // Hidden Field only active if "Beseelt"
                            Grid::make(3)
                                ->visible(fn (callable $get) => in_array('beseelt', $get('rs_erweiterungen')))
                                ->schema([
                                    Textinput::make('armor_arcan')
                                        ->label('RS Arkan')
                                        ->numeric()
                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                            return $state - self::setArmorHwp($get, $set);
                                        })
                                        ->live(),
                                    Textinput::make('armor_chaos')
                                        ->label('RS Chaos')
                                        ->numeric()
                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                            return $state - self::setArmorHwp($get, $set);
                                        })
                                        ->live(),
                                    Textinput::make('armor_spirit')
                                        ->label('RS Spirituell')
                                        ->numeric()
                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                            return $state - self::setArmorHwp($get, $set);
                                        })
                                        ->live(),
                                ]),
                            Select::make('rs_erweiterungen')
                                ->label('Erweiterungen')
                                ->multiple()
                                ->live()
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    self::setArmorStats($get, $set);
                                    if (!in_array('beseelt', $get('rs_erweiterungen'))) {
                                        $set('armor_arcan', null);
                                        $set('armor_chaos', null);
                                        $set('armor_spirit', null);
                                    }
                                })
                                ->options([
                                    'der modularität' => 'der Modularität (0 HwP)',
                                    'flexibel' => 'Flexibel (2 HwP)',
                                    'verstärkt' => 'Verstärkt (2 HwP)',
                                    'mechanisch' => 'Mechanisch (2 HwP)',
                                    'passgenau' => 'Passgenau (2 HwP)',
                                    'gelenkig' => 'Gelenkig (2 HwP)',
                                    'mit köcher' => 'mit Köcher (3 HwP)',
                                    'getarnt' => 'Getarnt (3 HwP)',
                                    'gleitend' => 'Gleitend (3 HwP)',
                                    'mit kletterausrüstung' => 'mit Kletterausrüstung (3 HwP)',
                                    'gehärtet' => 'Gehärtet (3 HwP)',
                                    'des artisten' => 'des Artisten (5 HwP)',
                                    'mit holster' => 'mit Holster (5 HwP)',
                                    'gleitend' => 'Gleitend (5 HwP)',
                                    'beseelt' => 'Beseelt (5 HwP)',
                                    'geläutert' => 'Geläutert (7 HwP)',
                                    'gepolstert' => 'Gepolstert (7 HwP)',
                                    'geölt' => 'Geölt (7 HwP)',
                                    'genietet' => 'Genietet (7 HwP)',
                                    'des elements' => 'des Elements (9 HwP)',
                                    ]),
                            Fieldset::make('verzauberungen')
                                ->label('Verzauberungen')
                                ->schema([
                                    Select::make('enchantment')
                                        ->label('Verzauberung')
                                        ->options(self::getEnchantments()),
                                    Select::make('enchantment_qs')
                                        ->label('QS Verzauberung')
                                        ->options(self::getQS()),
                                ]),
                    ]),
                Section::make('charm')
                    ->description('Werte des Talisman wählen')
                    ->visible(fn (callable $get) => $get('item_type') == 'Talisman')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Textinput::make('hwp')
                                    ->label('Handwerkspunkte')
                                    ->numeric()
                                    ->hint(function ($state, Get $get, Set $set) {
                                        return $state - self::setCharmHwp($get, $set);
                                    })
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        return $state - self::setCharmHwp($get, $set);
                                    })
                                    ->live(),
                                Textinput::make('kontrollwiderstand')
                                    ->label('Kontrollwiderstand')
                                    ->numeric()
                                    ->step(1)
                                    ->minvalue(1)
                                    ->maxvalue(99),
                                Textinput::make('traglast')
                                    ->label('Traglast')
                                    ->numeric(),
                            ]),
                        Grid::make(3)
                            ->schema([
                                Textinput::make('charm_arcan')
                                    ->label('RS Arkan')
                                    ->numeric()
                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                            return $state - self::setCharmHwp($get, $set);
                                        })
                                        ->live(),
                                Textinput::make('charm_chaos')
                                    ->label('RS Chaos')
                                    ->numeric()
                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                            return $state - self::setCharmHwp($get, $set);
                                        })
                                        ->live(),
                                Textinput::make('charm_spirit')
                                    ->label('RS Spirituell')
                                    ->numeric()
                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                            return $state - self::setCharmHwp($get, $set);
                                        })
                                        ->live(),
                            ]),
                            Select::make('ts_erweiterungen')
                                ->label('Erweiterungen')
                                ->multiple()
                                ->live()
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    self::setCharmStats($get, $set);
                                })
                                ->options([
                                    'der modularität' => 'der Modularität (0 HwP)',
                                    'der konzentration' => 'der Konzentration (2 HwP)',
                                    'der willenskraft' => 'der Willenskraft (2 HwP)',
                                    'der kommunikation' => 'der Kommunikation (2 HwP)',
                                    'der wahrnehmung' => 'der Wahrnehmung (2 HwP)',
                                    'der fokussierung' => 'der Fokussierung (3 HwP)',
                                    'der sympathie' => 'der Sympathie (3 HwP)',
                                    'der erinnerung' => 'der Erinnerung (3 HwP)',
                                    'der freiheit' => 'der Freiheit (5 HwP)',
                                    'des nachklangs' => 'des Nachklangs (5 HwP)',
                                    'der ordnung' => 'der Ordnung (5 HwP)',
                                    'der ruhe' => 'der Ruhe (5 HwP)',
                                    'der geduld' => 'der Geduld (5 HwP)',
                                    'der loyalität' => 'der Loyalität (7 HwP)',
                                    'der klarheit' => 'der Klarheit (7 HwP)',
                                    'der furchtlosigkeit' => 'der Furchtlosigkeit (7 HwP)',
                                    'der vielseitigkeit' => 'der Vielseitigkeit (7 HwP)',
                                    'der reflektion' => 'der Reflektion (9 HwP)',
                                    'der widersprüche' => 'der Widersprüche (9 HwP)',
                                ]),
                            Fieldset::make('verzauberungen')
                                ->label('Verzauberungen')
                                ->schema([
                                    Select::make('enchantment')
                                        ->label('Verzauberung')
                                        ->options(self::getEnchantments()),
                                    Select::make('enchantment_qs')
                                        ->label('QS Verzauberung')
                                        ->options(self::getQS()),
                                ]),

                    ]),
                Section::make('Schild')
                    ->description('Werte des Schild wählen')
                    ->visible(fn (callable $get) => $get('item_type') == 'Schild')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                            Textinput::make('hwp')
                                ->label('Handwerkspunkte')
                                ->numeric()
                                ->hint(function ($state, Get $get, Set $set) {
                                    return $state - self::setShieldHwp($get, $set);
                                })
                                ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                    return $state - self::setShieldHwp($get, $set);
                                })
                                ->live(),
                            Textinput::make('schild_verteidigung')
                                ->label('Verteidigungswert')
                                ->numeric(),
                            Textinput::make('traglast')
                                ->label('Traglast')
                                ->numeric(),
                            ]),
                            Grid::make(4)
                                ->schema([
                                Textinput::make('shield_schnitt')
                                    ->label('Rs Schnitt')
                                    ->numeric()
                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                            return $state - self::setShieldHwp($get, $set);
                                        })
                                        ->live(),
                                Textinput::make('shield_stumpf')
                                    ->label('Rs Stumpf')
                                    ->numeric()
                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                            return $state - self::setShieldHwp($get, $set);
                                        })
                                        ->live(),
                                Textinput::make('shield_stich')
                                    ->label('Rs Stich')
                                    ->numeric()
                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                            return $state - self::setShieldHwp($get, $set);
                                        })
                                        ->live(),
                                Textinput::make('shield_elementar')
                                    ->label('Rs Elementar')
                                    ->numeric()
                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                            return $state - self::setShieldHwp($get, $set);
                                        })
                                        ->live(),
                            ]),
                            Grid::make(3)
                                ->visible(fn (callable $get) => in_array('kosmischer schild', $get('sd_erweiterungen')))
                                ->schema([
                                    Textinput::make('shield_arcan')
                                        ->label('Rs Stumpf')
                                        ->numeric()
                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                            return $state - self::setShieldHwp($get, $set);
                                        })
                                        ->live(),
                                    Textinput::make('shield_chaos')
                                        ->label('Rs Stich')
                                        ->numeric()
                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                            return $state - self::setShieldHwp($get, $set);
                                        })
                                        ->live(),
                                    Textinput::make('shield_spirit')
                                        ->label('Rs Elementar')
                                        ->numeric()
                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                            return $state - self::setShieldHwp($get, $set);
                                        })
                                        ->live(),
                                ]),
                        Grid::make(2)
                            ->schema([
                                Textinput::make('offensivschild')
                                    ->label('Offensivschild')
                                    ->numeric()
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        self::setShieldHwp($get, $set);
                                    })
                                    ->live(),
                                Textinput::make('defensivschild')
                                    ->label('Defensivschild')
                                    ->dehydrated(false)
                                    ->numeric()
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        self::setShieldHwp($get, $set);
                                        self::setShieldStats($get, $set);
                                    })
                                    ->live(),
                            ]),
                        Select::make('sd_erweiterungen')
                            ->label('Erweiterungen')
                            ->multiple()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::setShieldStats($get, $set);
                                if (!in_array('kosmischer schild', $get('sd_erweiterungen'))) {
                                    $set('armor_arcan', null);
                                    $set('armor_chaos', null);
                                    $set('armor_spirit', null);
                                }
                            })
                            ->options([
                                'der modularität' => 'der Modularität (0 HwP)',
                                'stabil' => 'Stabil (1 HwP)',
                                'brechstange' => 'Brechstange (3 HwP)',
                                'gehärtet' => 'Gehärtet (3 HwP)',
                                'hakenschild' => 'Hakenschild (3 HwP)',
                                'schläger' => 'Schläger (3 HwP)',
                                'trommelschild' => 'Trommelschild (3 HwP)',
                                'buckler' => 'Buckler (5 HwP)',
                                'spiegelschild' => 'Spiegelschild (5 HwP)',
                                'turmschild' => 'Turmschild (5 HwP)',
                                'deckung' => 'Deckung (5 HwP)',
                                'kosmischer schild' => 'Kosmischer Schild (5 HwP)',
                                'schildstoß' => 'Schildstoß (7 HwP)',
                                'stachelschild' => 'Stachelschild (7 HwP)',
                                'des kampfflusses' => 'des Kampfflusses (7 HwP)',
                                'schildschlag' => 'Schildschlag (7 HwP)',
                                'übergroß' => 'Übergroß (9 HwP)',
                                ]),
                        Fieldset::make('verzauberungen')
                            ->label('Verzauberungen')
                            ->schema([
                                    Select::make('enchantment')
                                        ->label('Verzauberung')
                                        ->options(self::getEnchantments()),
                                    Select::make('enchantment_qs')
                                        ->label('QS Verzauberung')
                                        ->options(self::getQS()),
                                ]),
                    ]),
                Section::make('schmuckstück')
                    ->description('Werte des Schmuckstück wählen')
                    ->visible(fn (callable $get) => $get('item_type') == 'Schmuckstück')
                    ->schema([
                        Fieldset::make('verzauberungen')
                            ->label('Verzauberungen')
                            ->schema([
                                Select::make('enchantment')
                                    ->label('Verzauberung')
                                    ->options(self::getEnchantments()),
                                Select::make('enchantment_qs')
                                    ->label('QS Verzauberung')
                                    ->options(self::getQS()),
                            ]),
                    ]),

            ]);
    }

    public static function getQS(): array
    {
        return [
            'schlecht' => 'schlecht',
            'einfach' => 'einfach',
            'gewöhnlich' => 'gewöhnlich',
            'ungewöhnlich' => 'ungewöhnlich',
            'selten' => 'selten',
            'episch' => 'episch',
            'legendär' => 'legendär',
        ];
    }
    protected static function getEnchantments(): array
    {
        return [
            'der Künste' => 'der Künste',
            'des kosmischen Überflusses' => 'des kosmischen Überflusses',
            'der Vitalität' => 'der Vitalität',
            'der Erleichterung' => 'der Erleichterung',
            'der Hast' => 'der Hast',
            'des Schutzes' => 'des Schutzes',
            'des Eifers' => 'des Eifers',
            'der inneren Struktur' => 'der inneren Struktur',
            'der Bündelung' => 'der Bündelung',
            'der Rettung' => 'der Rettung',
            'des Bands' => 'des Bands',
            'des Echos' => 'des Echos',
            'des kosmischen Durchflusses' => 'des kosmischen Durchflusses',
        ];
    }
    protected static function getQSValues(): array
    {
        return [
            'schlecht' => [
                'AW' => 1, 'TW' => 'W4', 'VW' => 1, 'KW' => 1, 'pVW' => 1,
            ],
            'einfach' => [
                'AW' => 2, 'TW' => 'W4', 'VW' => 2, 'KW' => 2, 'pVW' => 2,
            ],
            'gewöhnlich' => [
                'AW' => 4, 'TW' => 'W6', 'VW' => 4, 'KW' => 4, 'pVW' => 4,
            ],
            'ungewöhnlich' => [
                'AW' => 7, 'TW' => 'W6', 'VW' => 7, 'KW' => 7, 'pVW' => 7,
            ],
            'selten' => [
                'AW' => 11, 'TW' => 'W8', 'VW' => 11, 'KW' => 11, 'pVW' => 11,
            ],
            'episch' => [
                'AW' => 16, 'TW' => 'W8', 'VW' => 16, 'KW' => 16, 'pVW' => 16,
            ],
            'legendär' => [
                'AW' => 22, 'TW' => 'W10', 'VW' => 22, 'KW' => 22, 'pVW' => 22,
            ],
        ];
    }
    protected static function getExtensionCosts(): array
    {
        // Die Schlüssel sind die reinen Namen der Erweiterungen (die in der DB gespeichert werden)
        // Die Werte sind die numerischen HwP-Kosten
        return [

            // Waffenerweiterungen
            'der einfachen Handhabung' => 1,
            'der Einzigartigkeit' => 1,
            'an der Kette' => 2,
            'der Kraftkontrolle' => 2,
            'des Attentäters' => 2,
            'der Flexibilität' => 2,
            'der Grausamkeit' => 3,
            'der Härtung' => 3,
            'des Duells' => 3,
            'des Tüftlers' => 3,
//          'mit Parierstange' => 3,  <-wird über extra Feld geregelt
            'des Gemetzels' => 3,
            'der Präzision' => 5,
            'der Zermürbung' => 5,
            'der Zielgenauigkeit' => 5,
            'der Brutalität' => 7,
            'der Effizienz' => 7,
            'der Kampfkunst' => 7,
            'der Schlagkraft' => 7,
            'der Revolution' => 9,
            'der Vorhut' => 9,
            'der Wucht' => 9,
            'des Hinterhalts' => 9,
            'des Jenseits' => 9,

            // Rüstungs-Erweiterungen
            'flexibel' => 2,
            'verstärkt' => 2,
            'mechanisch' => 2,
            'passgenau' => 2,
            'gelenkig' => 2,
            'mit köcher' => 3,
            'getarnt' => 3,
            'der Schwimmhilfe' => 3,
            'mit kletterausrüstung' => 3,
            'gehärtet' => 3,
            'des artisten' => 5,
            'mit holster' => 5,
            'gleitend' => 5,
            'beseelt' => 5,
            'geläutert' => 7,
            'gepolstert' => 7,
            'geölt' => 7,
            'genietet' => 7,
            'des elements' => 9,

            // Talisman-Erweiterungen
            'der konzentration' => 2,
            'der willenskraft' => 2,
            'der kommunikation' => 2,
            'der wahrnehmung' => 2,
            'der fokussierung' => 3,
            'der sympathie' => 3,
            'der erinnerung' => 3,
            'der freiheit' => 5,
            'des nachklangs' => 5,
            'der ordnung' => 5,
            'der ruhe' => 5,
            'der geduld' => 5,
            'der loyalität' => 7,
            'der klarheit' => 7,
            'der furchtlosigkeit' => 7,
            'der vielseitigkeit' => 7,
            'der reflektion' => 9,
            'der widersprüche' => 9,

            // Schild-Erweiterungen
            'stabil' => 1,
            'offensivschild' => 2,
            'defensivschild' => 2,
            'brechstange' => 3,
            'hakenschild' => 3,
            'schläger' => 3,
            'trommelschild' => 3,
            'buckler' => 5,
            'spiegelschild' => 5,
            'turmschild' => 5,
            'deckung' => 5,
            'kosmischer schild' => 5,
            'schildstoß' => 7,
            'stachelschild' => 7,
            'des kampfflusses' => 7,
            'schildschlag' => 7,
            'übergroß' => 9,
        ];
    }
    protected static function getDiceProgression(string $currentDice): string
    {
        $progression = [
            'W4' => 'W6',
            'W6' => 'W8',
            'W8' => 'W10',
            'W10' => '2W6',
            '2W6' => '2W8',
            '2W8' => '2W10',
            '2W10' => '2W12',
            '2W12' => '2W12', // Max Stufe
        ];
        // Gibt den nächsten Wert zurück, oder den aktuellen Wert, wenn er das Maximum erreicht hat
        return $progression[$currentDice] ?? $currentDice;
    }
    protected static function setWeaponStats(Get $get, Set $set): void
    {
        $quality = $get('quality');
        $qsValues = self::getQSValues();
        $traglast = 1;
        $count_dice = 1;
        $wp_erweiterungen = $get('wp_erweiterungen');

        if (!$quality || !isset($qsValues[$quality])) {
            $set('attackvalue', null);
            $set('tw', null);
            return;
        }

        $values = $qsValues[$quality];
        $set('attackvalue', $values['AW']);
        $currentDice = $values['TW'];

        if (in_array('der Schlagkraft', $wp_erweiterungen)) {
            $currentDice = self::getDiceProgression($currentDice);
        }
        $set('tw', $currentDice);

        if (in_array('der Eleganz', $wp_erweiterungen)) {
            $traglast += -1;
        }
        if ($get('waffenführung') == 'Zweihändig') {
            $traglast += 1;
        }
        if (in_array('der Modularität', $wp_erweiterungen)) {
            $traglast += 1;
        }
        if (in_array('der Wucht', $wp_erweiterungen)) {
            $traglast += 1;
        }
        $set('traglast', $traglast);

        $wp_erweiterungen = $get('wp_erweiterungen');
        if (in_array('der Wucht', $wp_erweiterungen)) {
            $count_dice += 1;
        }
        if ($get('waffenführung') == 'Zweihändig') {
            $count_dice += 1;
        }
        $set('count_dice', $count_dice);
    }
    protected static function setArmorStats(Get $get, Set $set): void
    {
        $quality = $get('quality');
        $qsValues = self::getQSValues();
        $traglast = 2;

        if (!$quality || !isset($qsValues[$quality])) {
            $set('pVW', null);
            return;
        }

        $values = $qsValues[$quality];
        $set('pVW', $values['pVW']);

        if (in_array('der modularität', $get('rs_erweiterungen'))) {
            $traglast += 1;
        }
        $set('traglast', $traglast);
    }
    protected static function setCharmStats(Get $get, Set $set): void
    {
        $quality = $get('quality');
        $qsValues = self::getQSValues();
        $traglast = 1;


        if (!$quality || !isset($qsValues[$quality])) {
            $set('kontrollwiderstand', null);
            return;
        }

        $values = $qsValues[$quality];
        $set('kontrollwiderstand', $values['KW']);

        if (in_array('der modularität', $get('ts_erweiterungen'))) {
            $traglast += 1;
        }
        $set('traglast', $traglast);
    }
    protected static function setShieldStats(Get $get, Set $set): void
    {
        $quality = $get('quality');
        $qsValues = self::getQSValues();
        $traglast = 1;


        if (!$quality || !isset($qsValues[$quality])) {
            $set('schild_verteidigung', null);
            return;
        }

        $values = $qsValues[$quality];
        $set('schild_verteidigung', $values['VW']+$get('defensivschild'));

        if (in_array('der modularität', $get('sd_erweiterungen'))) {
            $traglast += 1;
        }
        if (in_array('übergroß', $get('sd_erweiterungen'))) {
            $traglast += 1;
        }

        $set('traglast', $traglast);
    }
    protected static function setWeaponHwp(Get $get, Set $set): int
    {
        $wp_vw = $get('wp_vw');
        $selectedExtensions = $get('wp_erweiterungen');
        $costs = self::getExtensionCosts();
        $totalHwp = 0;

        foreach ($selectedExtensions as $extensionKey) {
            // Prüfe, ob der Schlüssel in unserem Mapping existiert
            $totalHwp += $costs[$extensionKey] ?? 0;
        }
        $totalHwp += $wp_vw *3/2;
        return $totalHwp;
    }
    protected static function setArmorHwp(Get $get, Set $set): int
    {
    $sum_rs = $get('armor_schnitt')+$get('armor_stumpf')+$get('armor_stich')+$get('armor_elementar')+$get('armor_arcan')+$get('armor_chaos')+$get('armor_spirit');

    $selectedExtensions = $get('rs_erweiterungen');
    $costs = self::getExtensionCosts();
    $totalHwp = 0;

    foreach ($selectedExtensions as $extensionKey) {
        // Prüfe, ob der Schlüssel in unserem Mapping existiert
        $totalHwp += $costs[$extensionKey] ?? 0;
    }
    $totalHwp += $sum_rs;
    return $totalHwp;
    }
    protected static function setCharmHwp(Get $get, Set $set): int
    {
        $sum_rs = $get('charm_arcan')+$get('charm_chaos')+$get('charm_spirit');

        $selectedExtensions = $get('ts_erweiterungen');
        $costs = self::getExtensionCosts();
        $totalHwp = 0;

        foreach ($selectedExtensions as $extensionKey) {
            // Prüfe, ob der Schlüssel in unserem Mapping existiert
            $totalHwp += $costs[$extensionKey] ?? 0;
        }
        $totalHwp += $sum_rs;
        return $totalHwp;
    }
    protected static function setShieldHwp(Get $get, Set $set): int
    {
        $sum_rs = $get('shield_schnitt')+$get('shield_stumpf')+$get('shield_stich')+$get('shield_elementar')+$get('shield_arcan')+$get('shield_chaos')+$get('shield_spirit');

        $sumExtrahwp = $get('offensivschild')+$get('defensivschild');
        $selectedExtensions = $get('ts_erweiterungen');
        $costs = self::getExtensionCosts();
        $totalHwp = 0;

        foreach ($selectedExtensions as $extensionKey) {
            // Prüfe, ob der Schlüssel in unserem Mapping existiert
            $totalHwp += $costs[$extensionKey] ?? 0;
        }
        $totalHwp += $sum_rs;
        $totalHwp += $sumExtrahwp;
        return $totalHwp;
    }

}
