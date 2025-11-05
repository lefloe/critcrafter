<?php

namespace App\Filament\Player\Resources\Equipment\Schemas;

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
                                    ->live()
                                    // 👇 RUFT ALLE spezifischen Funktionen auf
                                    ->afterStateUpdated(function ($livewire, Get $get, $set) {
                                        $livewire->emptyForm($set);
                                        self::setWeaponStats($get, $set);
                                        self::setArmorStats($get, $set);
                                        self::setCharmStats($get, $set);
                                        self::setShieldStats($get, $set);
                                    }),
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
                    ->description('Werte der Waffe wählen')
                    ->visible(fn (callable $get) => $get('item_type') == 'Waffe')
                    ->schema([
                        Grid::make(3)
                        ->schema([
                            Textinput::make('hwp')
                                ->label('Handwerkspunkte')
                                ->numeric()
                                ->step(1)
                                ->minValue(1)
                                ->maxValue(99),
                            Textinput::make('attackvalue')
                                ->label('Angriffswert')
                                ->numeric()
                                ->step(1),
                            Textinput::make('wptraglast')
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
                        ]),
                        Grid::make(2)
                            ->schema([
                            Radio::make('waffengattung')
                                ->options([
                                    'Nahkampfwaffe' => 'Nahkampfwaffe',
                                    'Fernkampfwaffe' => 'Fernkampfwaffe',
                                ]),
                            Select::make('damage_type')
                                ->Label('Leiteigenschaften (Schadensarten)')
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
                            ->options([
                                'der einfachen Handhabung' => 'der einfachen Handhabung(1 HwP)',
                                'der Einzigartigkeit' => 'der Einzigartigkeit(1 HwP)',
                                'an der Kette' => 'an der Kette(2 HwP)',
                                'der Kraftkontrolle' => 'der Kraftkontrolle(2 HwP)',
                                'des Attentäters' => 'des Attentäters(2 HwP)',
                                'der Grausamkeit' => 'der Grausamkeit(3 HwP)',
                                'der Härtung' => 'der Härtung(3 HwP)',
                                'des Duells' => 'des Duells(3 HwP)',
                                'des Tüftlers' => 'des Tüftlers(3 HwP)',
                                'mit Parierstange' => 'mit Parierstange(3 HwP)',
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
                                ])
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
                                    ->numeric(),
                                Textinput::make('pVW')
                                    ->label('passive Verteidigung')
                                    ->numeric(),
                                Textinput::make('armortraglast')
                                    ->label('Traglast')
                                    ->numeric(),
                            ]),
                        Grid::make(4)
                            ->schema([
                                Textinput::make('rs_schnitt')
                                    ->label('RS Schnitt')
                                    ->numeric(),
                                Textinput::make('rs_stumpf')
                                    ->label('RS Stumpf')
                                    ->numeric(),
                                Textinput::make('rs_stich')
                                    ->label('RS Stich')
                                    ->numeric(),
                                Textinput::make('rs_elementar')
                                    ->label('RS Elementar')
                                    ->numeric(),
                            ]),
                            Select::make('rs_erweiterungen')
                                ->label('Erweiterungen')
                                ->multiple()
                                ->live()
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    self::setArmorStats($get, $set);
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
                                    ->step(1)
                                    ->minValue(1)
                                    ->maxValue(99),
                                Textinput::make('kontrollwiderstand')
                                    ->label('Kontrollwiderstand')
                                    ->numeric()
                                    ->step(1)
                                    ->minvalue(1)
                                    ->maxvalue(99),
                                Textinput::make('charmtraglast')
                                    ->label('Traglast')
                                    ->numeric(),
                            ]),
                        Grid::make(3)
                            ->schema([
                                Textinput::make('rs_arcan')
                                    ->label('RS Arkan')
                                    ->numeric(),
                                Textinput::make('rs_chaos')
                                    ->label('RS Chaos')
                                    ->numeric(),
                                Textinput::make('rs_spirit')
                                    ->label('RS Spirituell')
                                    ->numeric(),
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
                                ->numeric(),
                            Textinput::make('schild_verteidigung')
                                ->label('Verteidigungswert')
                                ->numeric(),
                            Textinput::make('sdtraglast')
                                ->label('Traglast')
                                ->numeric(),
                                Textinput::make('rs_schnitt')
                                    ->label('Rüstungsschutz Schnitt')
                                    ->numeric(),
                                Textinput::make('rs_stumpf')
                                    ->label('Rüstungsschutz Stumpf')
                                    ->numeric(),
                                Textinput::make('rs_stich')
                                    ->label('Rüstungsschutz Stich')
                                    ->numeric(),
                            ]),
                        Select::make('sd_erweiterungen')
                            ->label('Erweiterungen')
                            ->multiple()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::setShieldStats($get, $set);
                            })
                            ->options([
                                'der modularität' => 'der Modularität (0 HwP)',
                                'stabil' => 'Stabil (1 HwP)',
                                'offensivschild' => 'Offensivschild (2 HwP)',
                                'defensivschild' => 'Defensivschild (2 HwP)',
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
    protected static function setWeaponStats(Get $get, Set $set): void
    {
        $quality = $get('quality');
        $qsValues = self::getQSValues();
        $traglast = 1;

        if (!$quality || !isset($qsValues[$quality])) {
            $set('attackvalue', null);
            $set('tw', null);
            return;
        }

        $values = $qsValues[$quality];
        $set('attackvalue', $values['AW']);
        $set('tw', $values['TW']);


        $wp_erweiterungen = $get('wp_erweiterungen');
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
        $set('wptraglast', $traglast);
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
        $set('armortraglast', $traglast);
    }
    protected static function setCharmStats(Get $get, Set $set): void
    {
        $quality = $get('quality');
        $qsValues = self::getQSValues();
        $traglast = 1;


        if (!$quality || !isset($qsValues[$quality])) {
            $set('kw', null);
            return;
        }

        $values = $qsValues[$quality];
        $set('kw', $values['KW']);

        if (in_array('der modularität', $get('ts_erweiterungen'))) {
            $traglast += 1;
        }
        $set('charmtraglast', $traglast);
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
        $set('schild_verteidigung', $values['VW']);

        if (in_array('der modularität', $get('sd_erweiterungen'))) {
            $traglast += 1;
        }
        if (in_array('übergroß', $get('sd_erweiterungen'))) {
            $traglast += 1;
        }

        $set('sdtraglast', $traglast);
    }


}
