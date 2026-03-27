<?php

namespace App\Filament\Player\Resources\Characters\Schemas;

use App\Filament\Resources\EquipmentResource;
use App\Helpers\equipmentextensionshelper;
use App\Helpers\limitClassabilitiesHelper;
use App\Models\Equipment;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;



class CharacterForm
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
                                            TextInput::make('name')
                                                ->columnSpan(2)
                                                ->label('Name')
                                                ->required()
                                                ->maxLength(255),
                                            Select::make('race')
                                                ->required()
                                                ->label('Rasse')
                                                ->options([
                                                    'Ainu' => 'Ainu',
                                                    'Alkonost' => 'Alkonost',
                                                    'Balachko' => 'Balachko',
                                                    'Bastet' => 'Bastet',
                                                    'Crocotta' => 'Crocotta',
                                                    'Karura' => 'Karura',
                                                    'Leshy' => 'Leshy',
                                                    'Vanaras' => 'Vanaras',
                                                    'Vodyanoy' => 'Vodyanoy',
                                                    'Vukodlak' => 'Vukodlak',
                                                    'Chepri' => 'Chepri',
                                                ]),
                                            TextInput::make('xp')
                                                ->Label('Erfahrungsgrad')
                                                ->numeric()
                                                ->default(1)
                                                ->live(onBlur: true)
                                                ->step(1)
                                                ->maxValue(22)
                                                ->required()
                                                ->minValue(1)
                                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                    self::setMainStateValue($get, $set);
                                                    self::maxEigenschaften($get, $set);
                                                    $eg_breakpoints = [1, 2, 4, 7, 11, 16, 20];
                                                    foreach (array_reverse($eg_breakpoints, true) as $qs_index => $eg_breakpoint) {
                                                        $actual_qs = $qs_index + 1;
                                                        if ((int) $state >= $eg_breakpoint) {
                                                            $qs = $actual_qs;
                                                            break;
                                                        }
                                                    }
                                                    $set('nw_quality', $qs);
                                                }),
                                        ]),
                                    Grid::make(2)
                                        ->schema([
                                            Select::make('racial_traits')
                                                ->label('Rassenmerkmale')
                                                ->multiple(3)
                                                ->live(onBlur: true)
                                                ->reactive()
                                                ->options([
                                                    'Apex' => 'Apex',
                                                    'Arborikol' => 'Arborikol',
                                                    'Balzkleid' => 'Balzkleid',
                                                    'Beutetier' => 'Beutetier',
                                                    'Pheromone' => 'Pheromone',
                                                    'Eingefettet' => 'Eingefettet',
                                                    'Fettpolster' => 'Fettpolster',
                                                    'Fleischig' => 'Fleischig',
                                                    'Geschuppt' => 'Geschuppt',
                                                    'Giftig' => 'Giftig',
                                                    'Glitschig' => 'Glitschig',
                                                    'Stacheln' => 'Stacheln',
                                                    'Kiemen' => 'Kiemen',
                                                    'Nachtsicht' => 'Nachtsicht',
                                                    'Panzer' => 'Panzer',
                                                    'Photosynthese' => 'Photosynthese',
                                                    'Raubtier / Hörner' => 'Raubtier / Hörner',
                                                    'Reittier' => 'Reittier',
                                                    'Samtpfote' => 'Samtpfote',
                                                    'Schleimspur' => 'Schleimspur',
                                                    'Schlinger' => 'Schlinger',
                                                    'Schwanz' => 'Schwanz',
                                                    'Schwingen' => 'Schwingen',
                                                    'Siebter Sinn' => 'Siebter Sinn',
                                                    'Spatulae' => 'Spatulae',
                                                    'Spitzohr' => 'Spitzohr',
                                                    'Sprunggelenke' => 'Sprunggelenke',
                                                    'Spucker' => 'Spucker',
                                                    'Spürnase' => 'Spürnase',
                                                    'Tarnmuster' => 'Tarnmuster',
                                                    'Tiefe Taschen' => 'Tiefe Taschen',
                                                    'Treibholz' => 'Treibholz',
                                                    'Unscheinbar' => 'Unscheinbar',
                                                    'Vielgliedrig' => 'Vielgliedrig',
                                                    'Vierbeiner' => 'Vierbeiner',
                                                    'Wurzeln' => 'Wurzeln',
                                                    'Zierlich / Kleinwüchsig' => 'Zierlich / Kleinwüchsig',
                                                    'Zweite Haut' => 'Zweite Haut',
                                                ]),
                                            Radio::make('wesen')
                                                ->required()
                                                ->inline()
                                                ->options([
                                                    'Biest' => 'Biest/Geist',
                                                    'Dämon' => 'Dämon/Spekter',
                                                ]),
                                        ]),
                                    RichEditor::make('description')
                                        ->label('Beschreibung')
                                        ->fileAttachmentsDisk('public')
                                        ->fileAttachmentsDirectory('descriptions')
                                        ->fileAttachmentsVisibility('public')
                                        ->disableToolbarButtons(['codeBlock','attachFiles',])
                                        ->default('
                                        <h3>Hintergrund und Persönlichkeit</h3>
                                        <p><strong>Herkunft:</strong> (Woher stammt der Charakter? Wer waren seine Eltern?)<br>
                                        <strong>Motivation:</strong> (Was treibt den Charakter an? Welche Ziele verfolgt er?)<br>
                                        <strong>Charakterzüge:</strong> (Welche Stärken und Schwächen hat der Charakter?)<br>
                                        <strong>Einschneidendes Ereignis:</strong> (Welches Erlebnis hat ihn geprägt?)<br>
                                        </p>
                                        ')
                                        ->columnSpanFull(),
                                ]),
                            Tabs\Tab::make('Eigenschaften')
                                ->schema([
                                            Fieldset::make('LeP, SeP & Ressourcen Bonus')
                                                ->columns([
                                                    'xl' => 3,
                                                    ])
                                                ->schema([
                                                    TextInput::make('bonus_lep')
                                                        ->label('LeP Bonus')
                                                        ->live()
                                                        ->numeric()
                                                        ->live(debounce: 300)
                                                        ->afterStateUpdatedJs(
                                                            <<<'JS'
                                                                $kobonus = parseInt($get('ko_bonus'))
                                                                $set('leps', parseInt($state) + $kobonus + parseInt($get('ko')))
                                                            JS
                                                        )
                                                        ->hint(function ($state, Get $get, Set $set) {
                                                            $result = self::BonusfromXp($get, $set)-$get('bonus_sep')-$get('bonus_re')-$state;
                                                            return $result;
                                                        }),
                                                    TextInput::make('bonus_sep')
                                                        ->label('SeP Bonus')
                                                        ->live(debounce: 500)
                                                        ->numeric()
                                                        ->afterStateUpdatedJs(
                                                            <<<'JS'
                                                                $set('seelenpunkte', parseInt($state) + 2 * parseInt($get('ch')));
                                                            JS
                                                        )
                                                        ->hint(function ($state, Get $get, Set $set) {
                                                            $result = self::BonusfromXp($get, $set)-$get('bonus_lep')-$get('bonus_re')-$state;
                                                            return $result;
                                                        }),
                                                    TextInput::make('bonus_re')
                                                        ->label('RE Bonus')
                                                        ->live(debounce: 500)
                                                        ->numeric()
                                                        ->live()
                                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                            self::setMainStateValue($get, $set);
                                                        })
                                                        ->hint(function ($state, Get $get, Set $set) {
                                                            $result = self::BonusfromXp($get, $set)-$get('bonus_lep')-$get('bonus_sep')-$state;;
                                                            return $result;
                                                        }),
                                                ]),
                                    Section::make()
                                        ->compact()
                                        ->schema([
                                            Grid::make(3)
                                                ->schema([
                                                    Select::make('leiteigenschaft1')
                                                        ->label('Leiteigenschaft 1')
                                                        ->required()
                                                        ->live()
                                                        ->options([
                                                            'KO' => 'Konstitution',
                                                            'ST' => 'Stärke',
                                                            'AG' => 'Agilität',
                                                            'GE' => 'Geschick',
                                                            'WE' => 'Weisheit',
                                                            'IN' => 'Intuition',
                                                            'MU' => 'Mut',
                                                            'CH' => 'Charisma',
                                                        ])
                                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                            $set('archetype', self::getArchetype($state, $get('leiteigenschaft2')));
                                                            self::setMainStateValue($get, $set);
                                                            self::calculateLeps($get, $set);
                                                            self::getweaponskills($get, $set);
                                                            self::getaspectskills($get, $set);

                                                        })
                                                        ->afterStateHydrated(function ($state, Get $get, Set $set) {
                                                            $set('archetype', self::getArchetype($state, $get('leiteigenschaft2')));   //sets archetype
                                                            self::setMainStateValue($get, $set);
                                                            self::calculateLeps($get, $set);
                                                            self::getweaponskills($get, $set);
                                                            self::getaspectskills($get, $set);

                                                        }),
                                                    Select::make('leiteigenschaft2')
                                                        ->label('Leiteigenschaft 2')
                                                        ->required()
                                                        ->live()
                                                        ->options([
                                                            'KO' => 'Konstitution',
                                                            'ST' => 'Stärke',
                                                            'AG' => 'Agilität',
                                                            'GE' => 'Geschick',
                                                            'WE' => 'Weisheit',
                                                            'IN' => 'Intuition',
                                                            'MU' => 'Mut',
                                                            'CH' => 'Charisma',
                                                        ])
                                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                            $set('archetype', self::getArchetype($state, $get('leiteigenschaft1')));   //sets archetype
                                                            self::setMainStateValue($get, $set);
                                                            self::calculateLeps($get, $set);
                                                        })
                                                        ->afterStateHydrated(function ($state, Get $get, Set $set) {
                                                            $set('archetype', self::getArchetype($state, $get('leiteigenschaft1')));   //sets archetype
                                                            self::setMainStateValue($get, $set);
                                                            self::calculateLeps($get, $set);
                                                        }),
                                                    Toggle::make('ko_toggle')
                                                        ->label('KO für LeP verwenden')
                                                        ->inline(false)
                                                        ->live()
                                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                            self::setMainStateValue($get, $set);
                                                            self::calculateLeps($get, $set);
                                                        }),
                                                ]),
                                        ]),
                                    Section::make('Eigenschaften')
                                        ->compact()
                                        ->description(function (Get $get, Set $set) {
                                            $result = self::maxEigenschaften($get, $set);

                                            return "{$result['sumeig']}   von {$result['maxeig']} Punkten vergeben. Maximal {$result['limit']} pro Eigenschaft.";
                                        })
                                        ->schema([
                                            Grid::make([
                                                'default' => 2,
                                                'lg' => 4,
                                            ])
                                                ->schema([
                                                    TextInput::make('ko') // Konstitution
                                                    ->label('Konstitution (KO)')
                                                        ->numeric()
                                                        ->minValue(0)
                                                        ->maxValue(function (callable $get) {
                                                            return $get('limit') ?? 100;
                                                        })
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                            self::calculateLeps($get, $set);
                                                            self::maxEigenschaften($get, $set);
                                                            self::setMainStateValue($get, $set);
                                                            self::setAttributeBonus($state, $get, $set);
                                                        })
                                                        ->required(),
                                                    TextInput::make('st') // Stärke
                                                    ->label('Stärke (ST)')
                                                        ->numeric()
                                                        ->minValue(0)
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                            $set('tragkraft', $state);
                                                            self::maxEigenschaften($get, $set);
                                                            self::setMainStateValue($get, $set);
                                                            self::setAttributeBonus($state, $get, $set);
                                                        })
                                                        ->required(),
                                                    TextInput::make('ag') // Agilität
                                                    ->label('Agilität (AG)')
                                                        ->numeric()
                                                        ->minValue(0)
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                            $set('gs_leib', round($state / 2));
                                                            self::maxEigenschaften($get, $set);
                                                            self::setMainStateValue($get, $set);
                                                            self::setAttributeBonus($state, $get, $set);
                                                        })
                                                        ->required(),
                                                    TextInput::make('ge') // Geschick
                                                    ->label('Geschick (GE)')
                                                        ->numeric()
                                                        ->minValue(0)
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                            $bonus = ($state < 12) ? 0 : $state - 12;
                                                            $set('handwerksbonus', $bonus);
                                                            self::maxEigenschaften($get, $set);
                                                            self::setMainStateValue($get, $set);
                                                            self::setAttributeBonus($state, $get, $set);
                                                        })
                                                        ->required(),
                                                    TextInput::make('we') // Weisheit
                                                    ->label('Weisheit (WE)')
                                                        ->numeric()
                                                        ->minValue(0)
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                            $set('kontrollwiderstand', $state - 12);
                                                            self::maxEigenschaften($get, $set);
                                                            self::setMainStateValue($get, $set);
                                                            self::setAttributeBonus($state, $get, $set);
                                                        })
                                                        ->required(),
                                                    TextInput::make('in') // Instinkt
                                                    ->label('Instinkt (IN)')
                                                        ->numeric()
                                                        ->minValue(0)
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                            $set('initiative', round($state / 2 + $get('bonus_ini')));
                                                            self::maxEigenschaften($get, $set);
                                                            self::setMainStateValue($get, $set);
                                                            self::setAttributeBonus($state, $get, $set);
                                                        })
                                                        ->required(),
                                                    TextInput::make('mu') // Mut
                                                    ->label('Mut (MU)')
                                                        ->numeric()
                                                        ->minValue(0)
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                            $set('verteidigung', $state - 12);
                                                            self::maxEigenschaften($get, $set);
                                                            self::setMainStateValue($get, $set);
                                                            self::setAttributeBonus($state, $get, $set);
                                                        })
                                                        ->required(),
                                                    TextInput::make('ch') // Charisma
                                                    ->label('Charisma (CH)')
                                                        ->numeric()
                                                        ->minValue(0)
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                            $set('seelenpunkte', $state * 2 + $get('bonus_sep'));;
                                                            self::setMainStateValue($get, $set);
                                                            self::maxEigenschaften($get, $set);
                                                            self::setAttributeBonus($state, $get, $set);
                                                        })
                                                        ->required(),
                                                ]),
                                        ]),
                                ]),
                            Tabs\Tab::make('Fertigkeiten')
                                ->schema([
                                    Section::make('Klassenfertigkeiten')
                                        ->compact()
                                        ->schema([
                                            Grid::make(2)
                                                ->schema([
                                                    Fieldset::make('fieldsetclassability')
                                                        ->hiddenLabel()
                                                        ->contained(false)
                                                        ->dense()
                                                        ->schema([
                                                            Select::make('classability1')
                                                                ->columnSpanFull()
                                                                ->label('Klassenfertigkeiten I')
                                                                ->multiple()
                                                                ->live()
                                                                ->options([
                                                                    'Eigenschaftsbonus' => 'Eigenschaftsbonus',
                                                                    'Basistalentbonus' => 'Basistalentbonus',
                                                                    'Begabung' => 'Begabung',
                                                                    'Eingebung' => 'Eingebung',
                                                                    'Alle meine Schäfchen' => 'Alle meine Schäfchen',
                                                                    'Alles wird verwertet' => 'Alles wird verwertet',
                                                                    'An Leibern laben' => 'An Leibern laben',
                                                                    'Auf der Lauer' => 'Auf der Lauer',
                                                                    'Aufmerksamer Zuhörer' => 'Aufmerksamer Zuhörer',
                                                                    'Aura lesen' => 'Aura lesen',
                                                                    'Aus dem Nichts' => 'Aus dem Nichts',
                                                                    'Bewegungsmuster' => 'Bewegungsmuster',
                                                                    'Blut und Schweiß' => 'Blut und Schweiß',
                                                                    'Eins mit der Seele' => 'Eins mit der Seele',
                                                                    'Elementare Essenz - wähle zwei' => 'Elementare Essenz - wähle zwei',
                                                                    'Elementarer Anker' => 'Elementarer Anker',
                                                                    'Empathie' => 'Empathie',
                                                                    'Erneuerung' => 'Erneuerung',
                                                                    'Fahler Schleier' => 'Fahler Schleier',
                                                                    'Fluchwirker' => 'Fluchwirker',
                                                                    'Frost- und Brandkontrolle' => 'Frost- und Brandkontrolle',
                                                                    'Furchtlos' => 'Furchtlos',
                                                                    'Ganz selbstverständlich' => 'Ganz selbstverständlich',
                                                                    'Gebildet' => 'Gebildet',
                                                                    'Geschärfte Klingen' => 'Geschärfte Klingen',
                                                                    'Gestählter Wille' => 'Gestählter Wille',
                                                                    'Gute Gene' => 'Gute Gene',
                                                                    'Im Antlitz der Gefahr' => 'Im Antlitz der Gefahr',
                                                                    'In Stellung' => 'In Stellung',
                                                                    'Kampfgespür' => 'Kampfgespür',
                                                                    'Kein Entrinnen' => 'Kein Entrinnen',
                                                                    'Kosmische Schnitzerei' => 'Kosmische Schnitzerei',
                                                                    'Krüge zerdeppern' => 'Krüge zerdeppern',
                                                                    'Laute Stimme' => 'Laute Stimme',
                                                                    'Lautlos' => 'Lautlos',
                                                                    'Leide!' => 'Leide!',
                                                                    'Machtvoller Wille' => 'Machtvoller Wille',
                                                                    'Massaker' => 'Massaker',
                                                                    'Mit Schwung' => 'Mit Schwung',
                                                                    'Nexuspunkt' => 'Nexuspunkt',
                                                                    'Offene Pforten' => 'Offene Pforten',
                                                                    'Opportunist' => 'Opportunist',
                                                                    'Randnotizen' => 'Randnotizen',
                                                                    'Ruf des Vertrauten' => 'Ruf des Vertrauten',
                                                                    'Runenschmuck' => 'Runenschmuck',
                                                                    'Stummer Diener' => 'Stummer Diener',
                                                                    'Synchronreflex' => 'Synchronreflex',
                                                                    'Taktischer Rückzug' => 'Taktischer Rückzug',
                                                                    'Tierflüsterer' => 'Tierflüsterer',
                                                                    'Totem' => 'Totem',
                                                                    'Treuer Weggefährte' => 'Treuer Weggefährte',
                                                                    'Unleben' => 'Unleben',
                                                                    'Unnötiger Balast' => 'Unnötiger Balast',
                                                                    'Unter meinem Schutz' => 'Unter meinem Schutz',
                                                                    'Versatiler Kampfstil' => 'Versatiler Kampfstil',
                                                                    'Verzerrter Schleier' => 'Verzerrter Schleier',
                                                                    'Vorbereitung' => 'Vorbereitung',
                                                                    'Wer austeilt, kann auch einstecken' => 'Wer austeilt, kann auch einstecken',
                                                                    'Wut' => 'Wut',
                                                                    'Zur Deckung' => 'Zur Deckung'
                                                                    ])
                                                                ->afterstateUpdated(function ($state, Get $get, Set $set) {
                                                                    limitClassabilitiesHelper::limitClassability1($get, $set);
                                                                    $true = in_array('Wer austeilt, kann auch einstecken', (array) $state);
                                                                    $set('nw_vw', $true ? $get('xp') : 0);
                                                                })
                                                                ->hint(function (Get $get, Set $set) {
                                                                    $value = count($get('classability1')) ?? 10;
                                                                    $limit = limitClassabilitiesHelper::limitClassability1($get, $set);
                                                                    return $limit - $value;
                                                                }),
                                                            Select::make('classability2')
                                                                ->columnSpanFull()
                                                                ->label('Klassenfertigkeiten II')
                                                                ->multiple()
                                                                ->live()
                                                                ->options([
                                                                    'Ablenkungsmanöver' => 'Ablenkungsmanöver',
                                                                    'Adrenalin' => 'Adrenalin',
                                                                    'Astralreise' => 'Astralreise',
                                                                    'Auf der Tonspur' => 'Auf der Tonspur',
                                                                    'Aus dem Ärmel' => 'Aus dem Ärmel',
                                                                    'Aus dem besten Holz geschnitzt' => 'Aus dem besten Holz geschnitzt',
                                                                    'Aus dem Handgelenk' => 'Aus dem Handgelenk',
                                                                    'Austauschbar' => 'Austauschbar',
                                                                    'Blick dahinter' => 'Blick dahinter',
                                                                    'Blitzschnell' => 'Blitzschnell',
                                                                    'Blutmagie' => 'Blutmagie',
                                                                    'Bollwerk' => 'Bollwerk',
                                                                    'Borke' => 'Borke',
                                                                    'Dunkles Geschenk' => 'Dunkles Geschenk',
                                                                    'Eingebung' => 'Eingebung',
                                                                    'Einklang' => 'Einklang',
                                                                    'En Garde' => 'En Garde',
                                                                    'Esoterische Kunst' => 'Esoterische Kunst',
                                                                    'Faust der Elemente' => 'Faust der Elemente',
                                                                    'Für den Kampf geschaffen' => 'Für den Kampf geschaffen',
                                                                    'Gedankenschutz' => 'Gedankenschutz',
                                                                    'Gesplitterte Bindung' => 'Gesplitterte Bindung',
                                                                    'Gewusst wie' => 'Gewusst wie',
                                                                    'Gnadenlos' => 'Gnadenlos',
                                                                    'Grenzenloses Wissen' => 'Grenzenloses Wissen',
                                                                    'Herr über den Verstand' => 'Herr über den Verstand',
                                                                    'Hinter dem Vorhang' => 'Hinter dem Vorhang',
                                                                    'In Erwartung' => 'In Erwartung',
                                                                    'Kampfgeschirr' => 'Kampfgeschirr',
                                                                    'Karmale Barriere' => 'Karmale Barriere',
                                                                    'Kaskade' => 'Kaskade',
                                                                    'Kettenreaktion' => 'Kettenreaktion',
                                                                    'Kreuzblock' => 'Kreuzblock',
                                                                    'Lebensentzug' => 'Lebensentzug',
                                                                    'Machtvolle Runen' => 'Machtvolle Runen',
                                                                    'Mit allen Sinnen' => 'Mit allen Sinnen',
                                                                    'Mit dem flachen Ende' => 'Mit dem flachen Ende',
                                                                    'Mit einer Stimme' => 'Mit einer Stimme',
                                                                    'Mit Gewalt' => 'Mit Gewalt',
                                                                    'Reiche Beute' => 'Reiche Beute',
                                                                    'Resonanz' => 'Resonanz',
                                                                    'Ritualisiert' => 'Ritualisiert',
                                                                    'Scheitern ist keine Option' => 'Scheitern ist keine Option',
                                                                    'Schild des Rechtschaffenen' => 'Schild des Rechtschaffenen',
                                                                    'Schwachstellen aufdecken' => 'Schwachstellen aufdecken',
                                                                    'Seelenentzug' => 'Seelenentzug',
                                                                    'Seeleninstrument' => 'Seeleninstrument',
                                                                    'Spiegel des Willens' => 'Spiegel des Willens',
                                                                    'Stille' => 'Stille',
                                                                    'Synergetik' => 'Synergetik',
                                                                    'Tausend Klingen' => 'Tausend Klingen',
                                                                    'Unbemerkt' => 'Unbemerkt',
                                                                    'Unerschöpflich' => 'Unerschöpflich',
                                                                    'Verrotte!' => 'Verrotte!',
                                                                    'Volle Kontrolle' => 'Volle Kontrolle',
                                                                    'Von allen Seiten' => 'Von allen Seiten',
                                                                    'Wandelndes Land' => 'Wandelndes Land',
                                                                    ])
                                                                ->afterstateUpdated(function (Get $get, Set $set) {
                                                                    limitClassabilitiesHelper::limitclassability2($get, $set);
                                                                    self::getweaponskills($get, $set);
                                                                    self::getaspectskills($get, $set);
                                                                })
                                                                ->hint(function (Get $get, Set $set) {
                                                                    $value = count($get('classability2')) ?? 10;
                                                                    $limit = limitClassabilitiesHelper::limitclassability2($get, $set);
                                                                    return $limit - $value;
                                                                }),
                                                            Select::make('classability3')
                                                                ->columnSpanFull()
                                                                ->label('Klassenfertigkeiten III')
                                                                ->multiple()
                                                                ->live()
                                                                ->options([
                                                                    'Alles oder nichts' => 'Alles oder nichts',
                                                                    'Armee der Toten' => 'Armee der Toten',
                                                                    'Avatar' => 'Avatar',
                                                                    'Blutsbruderschaft' => 'Blutsbruderschaft',
                                                                    'Eingebung' => 'Eingebung',
                                                                    'Fest für die Sinne' => 'Fest für die Sinne',
                                                                    'Flèche' => 'Flèche',
                                                                    'Fluss des Kosmos' => 'Fluss des Kosmos',
                                                                    'Gestaltwandler' => 'Gestaltwandler',
                                                                    'Im Leid suhlen' => 'Im Leid suhlen',
                                                                    'Jäger Stufe III' => 'Jäger Stufe III',
                                                                    'Kontrolle Rang 3 Wesen' => 'Kontrolle Rang 3 Wesen',
                                                                    'Krieger Stufe III' => 'Krieger Stufe III',
                                                                    'Lohn der Gläubigen' => 'Lohn der Gläubigen',
                                                                    'Magus Stufe III' => 'Magus Stufe III',
                                                                    'Meuchler' => 'Meuchler',
                                                                    'Mönch Stufe III' => 'Mönch Stufe III',
                                                                    'Pirscher Stufe III' => 'Pirscher Stufe III',
                                                                    'Reflektierter Geist' => 'Reflektierter Geist',
                                                                    'Runenschnitzer Stufe III' => 'Runenschnitzer Stufe III',
                                                                    'Sekundenbruchteil' => 'Sekundenbruchteil',
                                                                    'Trefferwürfel Steigerung' => 'Trefferwürfel Steigerung',
                                                                    'Unterjocht' => 'Unterjocht',
                                                                    'Unzertrennlich' => 'Unzertrennlich',
                                                                    'Urteil der Arena' => 'Urteil der Arena',
                                                                    'Zwischen die Schuppen' => 'Zwischen die Schuppen',
                                                                    'Zwischen Leben und Tod' => 'Zwischen Leben und Tod',
                                                                    ])
                                                                ->afterstateUpdated(function (Get $get, Set $set) {
                                                                    limitClassabilitiesHelper::limitclassability3($get, $set);
                                                                })
                                                                ->hint(function (Get $get, Set $set) {
                                                                    $value = count($get('classability3')) ?? 10;
                                                                    $limit = limitClassabilitiesHelper::limitclassability3($get, $set);
                                                                    return $limit - $value;
                                                                }),
                                                        ]),
                                                    Fieldset::make('fieldsetclassability')
                                                        ->hiddenLabel()
                                                        ->contained(false)
                                                        ->dense()
                                                        ->afterStateUpdated(
                                                            function ($state, Get $get, Set $set) {
                                                                limitClassabilitiesHelper::limitClassability1($get, $set);
                                                                limitClassabilitiesHelper::limitclassability2($get, $set);
                                                                limitClassabilitiesHelper::limitclassability3($get, $set);
                                                                self::setAttributeBonus($state, $get, $set);
                                                                self::getaspectskills($get, $set);
                                                                self::setMainStateValue($get, $set);
                                                            })
                                                        ->schema([
//                                                            Repeater::make('boni')
//                                                                ->hiddenLabel()
//                                                                ->live()
//                                                                ->columnSpanFull()
//                                                                ->addActionLabel('Allgemeine Klassenfertigkeiten')
//                                                                ->simple(
//                                                                    Select::make('bonus')
//                                                                        ->options([
//                                                                        'Eigenschaft' => [
//                                                                            'ko' => 'Konstitution',
//                                                                            'st' => 'Stärke',
//                                                                            'ag' => 'Agilität',
//                                                                            'ge' => 'Geschick',
//                                                                            'we' => 'Weisheit',
//                                                                            'in' => 'Intuition',
//                                                                            'mu' => 'Mut',
//                                                                            'ch' => 'Charisma',
//                                                                        ],
//                                                                        'Basistalent' => [
//                                                                            'Zähigkeit' => 'Zähigkeit',
//                                                                            'Kraftakt' => 'Kraftakt',
//                                                                            'Körperbeh' => 'Körperbeh.',
//                                                                            'Fingerfer' => 'Fingerfer.',
//                                                                            'Konzentration' => 'Konzentration',
//                                                                            'Wahrnehmung' => 'Wahrnehmung',
//                                                                            'Willenskraft' => 'Willenskraft',
//                                                                            'Kommunikation' => 'Kommunikation',
//                                                                            ],
//                                                                    ])
//                                                                ),
                                                            Repeater::make('sonderboni')
                                                                ->label('Sonderfertigkeiten')
                                                                ->compact()
                                                                ->live()
                                                                ->columnSpanFull()
                                                                ->addActionLabel('Sonderbonus hinzufügen')
                                                                ->schema([
                                                                    Select::make('special_skill_type')
                                                                        ->hiddenlabel()
                                                                        ->required()
                                                                        ->options([
                                                                            'eingebung' => 'Eingebung (+1 Skill-Limit)',
                                                                            'basistalent_bonus' => 'Basistalentbonus',
                                                                            'eigenschaft_bonus' => 'Eigenschaftsbonus',
                                                                        ])
                                                                        ->live(),
                                                                    TextInput::make('eingebung_info')
                                                                        ->hiddenLabel()
                                                                        ->visible(fn (Get $get) => $get('special_skill_type') === 'eingebung')
                                                                        ->placeholder('Aspekt- oder Waffenfertigkeit eintragen'),
                                                                    Select::make('basistalent')
                                                                        ->visible(fn (Get $get) => $get('special_skill_type') === 'basistalent_bonus')
                                                                        ->inlineLabel()
                                                                        ->options([
                                                                            'Zähigkeit' => 'Zähigkeit',
                                                                            'Kraftakt' => 'Kraftakt',
                                                                            'Körperbeh' => 'Körperbeh.',
                                                                            'Fingerfer' => 'Fingerfer.',
                                                                            'Konzentration' => 'Konzentration',
                                                                            'Wahrnehmung' => 'Wahrnehmung',
                                                                            'Willenskraft' => 'Willenskraft',
                                                                            'Kommunikation' => 'Kommunikation',
                                                                        ]),
                                                                    Select::make('eigenschaft')
                                                                        ->visible(fn (Get $get) => $get('special_skill_type') === 'eigenschaft_bonus')
                                                                        ->inlineLabel()
                                                                        ->options([
                                                                            'ko' => 'Konstitution',
                                                                            'st' => 'Stärke',
                                                                            'ag' => 'Agilität',
                                                                            'ge' => 'Geschick',
                                                                            'we' => 'Weisheit',
                                                                            'in' => 'Intuition',
                                                                            'mu' => 'Mut',
                                                                            'ch' => 'Charisma',
                                                                        ]),
                                                                ]),
                                                            ]),
                                                ]),
                                        ]),
                                    Fieldset::make('Handwerk und Überlieferungen')
                                    ->schema([
                                        Select::make('craftability')
                                            ->label('Spezialisierungen')
                                            ->multiple()
                                            ->live()
                                            ->afterstateUpdated(function (Get $get, Set $set) {
                                                self:self::limitcraftability($get, $set);
                                            })
                                            ->options([
                                                'Handelswaren' => 'Handelswaren',
                                                'Offensive Anwendungen' => 'Offensive Anwendungen',
                                                'Unterstützende Anwendungen' => 'Unterstützende Anwendungen',
                                                'Nahrungsmittel' => 'Nahrungsmittel',
                                                'Paraphernalia & Leiber' => 'Paraphernalia & Leiber',
                                                'Rüstungen & Schilde' => 'Rüstungen & Schilde',
                                                'Schmuckstücke & Talismane' => 'Schmuckstücke & Talismane',
                                                'Verzauberungen' => 'Verzauberungen',
                                                'Waffen' => 'Waffen',
                                            ])
                                            ->hint(function (Get $get, Set $set) {
                                                $value = count($get('craftability')) ?? 10;
                                                $limit = self::limitcraftability($get, $set);
                                                return $limit - $value;
                                            }),
                                        Select::make('lore')
                                            ->label('Überlieferungen')
                                            ->multiple()
                                            ->live()
                                            ->options([
                                                'Kernlande' => 'Kernlande',
                                                'Der hohe Norden' => 'Der hohe Norden',
                                                'Die fruchtbaren Ebenen' => 'Die fruchtbaren Ebenen',
                                                'Das Dschungelreich' => 'Das Dschungelreich',
                                                'Kantropent' => 'Kantropent',
                                                'Der erbarmungslose Süden' => 'Der erbarmungslose Süden',
                                                'Die Angerlande' => 'Die Angerlande',
                                                'Das Nimmerlicht' => 'Das Nimmerlicht',
                                                'Spiegelwelt' => 'Spiegelwelt',
                                                'Splitterwelt' => 'Splitterwelt',
                                                'Unterwelt' => 'Unterwelt',
                                                'Baukunst und Architektur' => 'Baukunst und Architektur',
                                                'Seefahrt und Navigation' => 'Seefahrt und Navigation',
                                                'Schrift und Sprache' => 'Schrift und Sprache',
                                                'Fauna und Flora' => 'Fauna und Flora',
                                                'Mathematik und Messkunst' => 'Mathematik und Messkunst',
                                                'Kriegsführung und Taktik' => 'Kriegsführung und Taktik',
                                                'Landwirtschaft und Geologie' => 'Landwirtschaft und Geologie',
                                                'Fahrzeuge und Lastwesen' => 'Fahrzeuge und Lastwesen',
                                                'Äthertech' => 'Äthertech',
                                                'Der Kosmos' => 'Der Kosmos',
                                                'Legenden und Mythen' => 'Legenden und Mythen',
                                                'Prophezeiungen und Omen' => 'Prophezeiungen und Omen',
                                                'Orte der Macht' => 'Orte der Macht',
                                                'Die Aspekte' => 'Die Aspekte',
                                                'Das zersplitterte Erbe' => 'Das zersplitterte Erbe',
                                                ])
                                            ->hint(function ($state, Get $get, Set $set) {
                                                $value = count($state);
                                                $true = in_array('Aufmerksamer Zuhörer', (array) $get('classability1'));
                                                $limit = $true ? 6 : 4;
                                                return $limit - $value;
                                            }),
                                            ]),
                                    Section::make('Fertigkeiten')
                                        ->compact()
                                        ->description(function (Get $get, Set $set) {
                                            $result = self::limitskills($get, $set);
                                            return  count($result['flatList']). ' von ' .$result['limit']. ' Aspekt- und Waffenfertigkeiten ausgewählt';
                                        })
                                        ->schema([
                                            Grid::make(2)
                                                ->schema([
                                                    Select::make('skill_weapon')
                                                        ->hint(function ($state, Get $get, Set $set) {
                                                            $result = self::limitskills($get, $set);
                                                            return  $result['limit']-count($result['flatList']);
                                                        })
                                                        ->multiple()
                                                        ->optionsLimit(180)
                                                        ->live()
                                                        ->options(function (Get $get, Set $set) {
                                                            return self::getweaponskills($get, $set);
                                                        })
                                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                                            self::limitskills($get, $set);
                                                        }),
                                                    Select::make('skill_aspect')
                                                        ->hint(function ($state, Get $get, Set $set) {
                                                            $result = self::limitskills($get, $set);
                                                            return  $result['limit']-count($result['flatList']);
                                                        })
                                                        ->multiple()
                                                        ->optionsLimit(180)
                                                        ->live()
                                                        ->options(function (Get $get, Set $set) {
                                                            return self::getaspectskills($get, $set);
                                                        })
                                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                                            self::limitskills($get, $set);
                                                        }),
                                                ])
                                        ])
                                ]),
                            Tabs\Tab::make('Ausrüstung')
                                ->schema([
                                    Section::make('Natürliche Waffe')
                                        ->compact()
                                        ->schema([
                                            Grid::make(2)
                                                ->schema([
                                                    CheckboxList::make('nw_gattung')
                                                        ->label('Waffengattung')
                                                        ->columns(2)
                                                        ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'beides nur Eins mit der Seele')
                                                        ->options([
                                                            'Nahkampf' => 'Nahkampf',
                                                            'Fernkampf' => 'Fernkampf (nur Spucker)',
                                                        ]),
                                                    Select::make('nw_damage_type')
                                                        ->label('Schadensart')
                                                        ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'zweite nur Raubtier/Hörner')
                                                        ->live()
                                                        ->multiple()
                                                        ->options([
                                                            'stumpf' => 'ST (Stumpf)',
                                                            'schnitt' => 'AG (Schnitt)',
                                                            'stich' => 'GE (Stich)',
                                                        ]),
                                                ]),
                                            Grid::make(5)
                                                ->inlineLabel()
                                                ->schema([
                                                    TextInput::make('nw_quality')
                                                        ->columnSpan(2)
                                                        ->label('QS')
                                                        ->disabled(),
                                                    Textinput::make('nw_aw')
                                                        ->label('AW')
                                                        ->numeric(),
                                                    Textinput::make('nw_vw')
                                                        ->label('VW')
                                                        ->numeric(),
                                                    Textinput::make('nw_tw')
                                                        ->label('TW')
                                                        ->numeric(),
                                                ]),
                                            Select::make('extensions')
                                            ->visible(fn (Get $get ) =>
                                            in_array('Vierbeiner', (array) $get('racial_traits'))
                                            )
                                            ->label('Erweiterungen')
                                            ->options(fn (Get $get, Set $set) =>
                                            equipmentextensionshelper::getWpExtensions($get, $set)
                                            )
                                            ->hint('nur Vierbeinig'),
                                        ]),
                                    Section::make('Ausrüstung anlegen')
                                        ->compact()
                                        ->description('Wähle die aktuelle Ausrüstung')
                                        ->schema([
                                            Repeater::make('characterEquipment')
                                                ->relationship('characterEquipment')
                                                ->schema(components: [
                                                    Select::make('equipment_id')
                                                        ->required()
                                                        ->label('Equipment')
                                                        ->relationship('equipment', 'name')
                                                        ->options(Equipment::available()->pluck('name', 'id'))
                                                        ->searchable()
                                                        ->requiredIf('equipment_id', fn ($state) => $state !== null),
                                                    Select::make('slot')
                                                        ->label('Wo angelegt')
                                                        ->requiredIf('equipment_id',fn (Get $get) => $get('equipment_id') !== null)
                                                        ->options([
                                                            'not_equipped' => 'nicht angelegt',
                                                            'right_arm' => 'rechte Hand',
                                                            'left_arm' => 'linke Hand',
                                                            'third_arm' => 'dritte Hand (nur Vielgliedrig)',
                                                            'forth_arm' => 'vierte Hand (nur Vielgliedrig)',
                                                            'armor' => 'Rüstung',
                                                            'talisman_1' => 'Talisman 1',
                                                            'talisman_2' => 'Talisman 2 (nur Resonanz)',
                                                            'jewelry1' => 'Schmuckstück 1',
                                                            'jewelry2' => 'Schmuckstück 2',
                                                            'jewelry3' => 'Schmuckstück 3',
                                                            'jewelry4' => 'Schmuckstück 4 (nur Gaben des Tempels)',
                                                            'jewelry5' => 'Schmuckstück 5 (nur Gaben des Tempels)',
                                                        ]),
                                                ])
                                                ->columns(2)
                                                ->defaultItems(0)
                                                ->addActionLabel('weitere Ausrüstung hinzufügen')
                                        ]),
                                ]),
                        ]),
                    //finished calculations for print
                        Tabs::make('Tabs')
                            ->columnSpan(4)
                            ->tabs([
                                Tabs\Tab::make('Seite 1')
                                ->inlineLabel()
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('archetype')
                                                ->label('Archetyp')
                                                ->disabled()
                                                ->dehydrated(),
                                            TextInput::make('main_stat_value')
                                                ->label('Ressourcen')
                                                ->disabled()
                                                ->extraFieldWrapperAttributes(['class' => 'components-locked'])
                                                ->dehydrated(),
                                        ]),
                                    Fieldset::make('Leib')
                                        ->columns([
                                            'default' => 1,
                                            'md' => 1,
                                            'xl' => 1,
                                        ])
                                        ->schema([
                                            Grid::make(4)
                                                ->schema([
                                                TextInput::make('ko_sum')
                                                    ->label('KO')
                                                    ->inlineLabel()
                                                    ->disabled()
                                                    ->dehydrated(),
                                                TextInput::make('st_sum')
                                                    ->label('ST')
                                                    ->inlineLabel()
                                                    ->disabled()
                                                    ->dehydrated(),
                                                TextInput::make('ag_sum')
                                                    ->label('AG')
                                                    ->inlineLabel()
                                                    ->disabled()
                                                    ->dehydrated(),
                                                TextInput::make('ge_sum')
                                                    ->label('GE')
                                                    ->inlineLabel()
                                                    ->disabled()
                                                    ->dehydrated(),
                                                ]),
                                            Grid::make(2)
                                                ->schema([
                                                    TextInput::make('leps')
                                                    ->label('LeP')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                    TextInput::make('tragkraft')
                                                        ->label('Tragkraft')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                    TextInput::make('gs_leib')
                                                        ->label('GS Leib')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                    TextInput::make('gs_seele')
                                                        ->label('GS Seele')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                    TextInput::make('zähigkeit_sum')
                                                        ->label('Zähigkeit')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                    TextInput::make('kraftakt_sum')
                                                        ->label('Kraftakt')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                    TextInput::make('körperbeherrschung_sum')
                                                        ->label('Körperbeherrschung')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                    TextInput::make('fingerfertigkeit_sum')
                                                        ->label('Fingerfertigkeit')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                ]),
                                        ]),
                                    Fieldset::make('Seele')
                                        ->columns([
                                            'default' => 1,
                                            'md' => 1,
                                            'xl' => 1,
                                        ])
                                        ->schema([
                                            Grid::make(4)
                                                ->schema([
                                                    TextInput::make('we_sum')
                                                        ->label('WE')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                    TextInput::make('in_sum')
                                                        ->label('IN')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                    TextInput::make('mu_sum')
                                                        ->label('MU')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                    TextInput::make('ch_sum')
                                                        ->label('CH')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                ]),
                                            Grid::make(2)
                                                ->schema([
                                                    TextInput::make('kontrollwiderstand')
                                                        ->label('KW')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                    TextInput::make('initiative')
                                                        ->label('Initiative')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                    TextInput::make('verteidigung')
                                                        ->label('Verteidigung')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                    TextInput::make('seelenpunkte')
                                                        ->label('Seelen-punkte')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                    TextInput::make('konzentration_sum')
                                                    ->label('Konzentration')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                    TextInput::make('wahrnehmung_sum')
                                                    ->label('Wahrnehmung')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                    TextInput::make('willenskraft_sum')
                                                        ->label('Willenskraft')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                    TextInput::make('kommunikation_sum')
                                                        ->label('Kommunikation')
                                                        ->disabled()
                                                        ->dehydrated(),
                                                ]),
                                        ]),
                                ]),
                                Tabs\Tab::make('Seite 2')
                                ->schema([
                                    Fieldset::make('Ausrüstung')
                                        ->columns([
                                            'default' => 1,
                                            'md' => 1,
                                            'xl' => 1,
                                        ])
                                    ->schema([
                                        Grid::make(4)
                                        ->schema([
                                            TextInput::make('armor')
                                            ->label('Rüstung')
                                            ->disabled(),
                                            TextInput::make('charm')
                                            ->label('Talisman')
                                            ->disabled(),
                                            TextInput::make('sum_rs')
                                            ->label('Gesamtrüstung')
                                            ->disabled(),
                                            TextInput::make('enchantment')
                                            ->label('Verzauberungen')
                                            ->disabled(),
                                        ])
                                    ])

                                ]),
                            ]),
                    ]),
                //hidden fields for calculations (hidden() and dehydrated())
                TextInput::make('ko_bonus')
                    ->hidden()
                    ->live()
                    ->numeric()
                    ->disabled()
                    ->default(0)
                    ->dehydrated(),
            ]);
    }


    protected static function getaspectskills(Get $get, Set $set): array
    {
        $weOptions = [
            'Corpus Cupla' => 'Corpus Cupla',
            'Lepos' => 'Lepos',
            'Ligo Spiri' => 'Ligo Spiri',
            'Lucida' => 'Lucida',
            'Auxillum' => 'Auxillum',
            'Collatio' => 'Collatio',
            'Principor' => 'Principor',
            'Sucus Constantia' => 'Sucus Constantia',
            'Vexillum' => 'Vexillum',
            'Celero' => 'Celero',
            'Forma Kinetia' => 'Forma Kinetia',
            'Ictos' => 'Ictos',
            'Proiectum' => 'Proiectum',
            'Pupa' => 'Pupa',
            'Corpus Nox' => 'Corpus Nox',
            'Exvocare Exterreo' => 'Exvocare Exterreo',
            'Maledictum' => 'Maledictum',
            'Perdita' => 'Perdita',
            'Tenebra' => 'Tenebra',
            'Confirma' => 'Confirma',
            'Corpus Forma' => 'Corpus Forma',
            'Corpus Morpha' => 'Corpus Morpha',
            'Debilitas' => 'Debilitas',
            'Forma Mutatio' => 'Forma Mutatio',
            'Iunctio' => 'Iunctio',
            'Porta Speculum' => 'Porta Speculum',
            'Crux' => 'Crux',
            'Veto Umbrax' => 'Veto Umbrax',
            'Vitae' => 'Vitae',
            'Custodia' => 'Custodia',
            'Percello' => 'Percello',
            'Pondus' => 'Pondus',
            'Spiri Duro' => 'Spiri Duro',
            'Vigil' => 'Vigil',
            'Corpus Renovo' => 'Corpus Renovo',
            'Curatio Morbus' => 'Curatio Morbus',
            'Duplici' => 'Duplici',
            'Phagia' => 'Phagia',
            ];

        $inOptions = [
            'Mollis' => 'Mollis',
            'Siccatio' => 'Siccatio',
            'Caligos' => 'Caligos',
            'Ambulaqua' => 'Ambulaqua',
            'Pundio' => 'Pundio',
            'Corpus Lapis' => 'Corpus Lapis',
            'Gravis' => 'Gravis',
            'Magnes' => 'Magnes',
            'Terra Motus' => 'Terra Motus',
            'Terra Sculpta' => 'Terra Sculpta',
            'Cyastaspino' => 'Cyastaspino',
            'Fricarcer' => 'Fricarcer',
            'Pellucidus' => 'Pellucidus',
            'Calyx' => 'Calyx',
            'Frigtreus' => 'Frigtreus',
            'Ahenum' => 'Ahenum',
            'Arsitis' => 'Arsitis',
            'Caminus' => 'Caminus',
            'Circuligne' => 'Circuligne',
            'Incendium' => 'Incendium',
            'Illuminos' => 'Illuminos',
            'Lux Columna' => 'Lux Columna',
            'Oculux' => 'Oculux',
            'Purgato' => 'Purgato',
            'Spiri Exvocare' => 'Spiri Exvocare',
            'Corpus Mutare' => 'Corpus Mutare',
            'Dumus' => 'Dumus',
            'Sano' => 'Sano',
            'Vocatus Bestia' => 'Vocatus Bestia',
            'Vocatus Pral' => 'Vocatus Pral',
            'Convertempa' => 'Convertempa',
            'Divinatio' => 'Divinatio',
            'Percutit' => 'Percutit',
            'Praeterivide' => 'Praeterivide',
            'Tardius' => 'Tardius',
            'Calefaciendo' => 'Calefaciendo',
            'Intu' => 'Intu',
            'Liberare' => 'Liberare',
            'Sonarus' => 'Sonarus',
            'Volaris' => 'Volaris',
            ];

        $muOptions = [
            'Inanis' => 'Inanis',
            'Reicio' => 'Reicio',
            'Veto Memoria' => 'Veto Memoria',
            'Vocare Interdict' => 'Vocare Interdict',
            'Dissaeptum' => 'Dissaeptum',
            'Impero' => 'Impero',
            'Legere' => 'Legere',
            'Plaga' => 'Plaga',
            'Vinco' => 'Vinco',
            'Vis' => 'Vis',
            'Magniforma' => 'Magniforma',
            'Pandemalum' => 'Pandemalum',
            'Simulacrum' => 'Simulacrum',
            'Vocatus Malum' => 'Vocatus Malum',
            'Ligo Irae' => 'Ligo Irae',
            'Porta Fracti' => 'Porta Fracti',
            'Terrere' => 'Terrere',
            'Trepidatio' => 'Trepidatio',
            'Vocare Phantasma' => 'Vocare Phantasma',
            'Cruciatus' => 'Cruciatus',
            'Malum Specio' => 'Malum Specio',
            'Mille Acus' => 'Mille Acus',
            'Tedium' => 'Tedium',
            'Vocare Tormentis' => 'Vocare Tormentis',
            'Corpus Verto' => 'Corpus Verto',
            'Morbus' => 'Morbus',
            'Pestis' => 'Pestis',
            'Rubigo' => 'Rubigo',
            'Coactus' => 'Coactus',
            'Corpo Sucus' => 'Corpo Sucus',
            'Porta Exterreo' => 'Porta Exterreo',
            'Veto Nexus' => 'Veto Nexus',
            'Vocare Inmortui' => 'Vocare Inmortui',
            'Atrox' => 'Atrox',
            'Concavum' => 'Concavum',
            'Dissolutium' => 'Dissolutium',
            'Venatio' => 'Venatio',
            'Vocare Furia' => 'Vocare Furia',
            ];

        $chOptions = [
            'Affectio' => 'Affectio',
            'Cupiditas' => 'Cupiditas',
            'Ines' => 'Ines',
            'Meretrix' => 'Meretrix',
            'Effio Spiri' => 'Effio Spiri',
            'Castigato' => 'Castigato',
            'Vinculum' => 'Vinculum',
            'Sermo' => 'Sermo',
            'Spiri Vitae' => 'Spiri Vitae',
            'Ars' => 'Ars',
            'Excogitus' => 'Excogitus',
            'Clavicarius' => 'Clavicarius',
            'Inspiratio' => 'Inspiratio',
            'Machina Vitam' => 'Machina Vitam',
            'Ferus' => 'Ferus',
            'Ico' => 'Ico',
            'Ira' => 'Ira',
            'Spiritelum' => 'Spiritelum',
            'Conventus' => 'Conventus',
            'Eminentia' => 'Eminentia',
            'Sensus' => 'Sensus',
            'Vocare Spiri' => 'Vocare Spiri',
            'Lacero Spiri' => 'Lacero Spiri',
            'Pax' => 'Pax',
            'Peregrinus' => 'Peregrinus',
            'Aegis' => 'Aegis',
            'Vocare Fidus' => 'Vocare Fidus',
            'Pertinax' => 'Pertinax',
            'Detineo' => 'Detineo',
            'Recuso' => 'Recuso',
            'Cavillor' => 'Cavillor',
            'Exeo' => 'Exeo',
            'Fortuna' => 'Fortuna',
            'Velox' => 'Velox',
            'Vocare Credo' => 'Vocare Credo',
            ];

        $arrayGroup =[
            'WE' => $weOptions,
            'IN' => $inOptions,
            'MU' => $muOptions,
            'CH' => $chOptions,
        ];

        $lekey = [
            'leiteigenschaft1' => $get('leiteigenschaft1'),
            'leiteigenschaft2' => $get('leiteigenschaft2'),
        ];


        $result = [];
        foreach ($lekey as $wert) {
            if (array_key_exists($wert, $arrayGroup)) {
                $result[$wert] = $arrayGroup[$wert];
            }
        }

        //Falls classability 2 array enthält Grenzenloses Wissen dann alle Optionen zurückgeben:
        if (in_array('Grenzenloses Wissen', $get('classability2'))) {
            return $arrayGroup;
        }

        return $result;
    }

    protected static function getweaponskills(Get $get, Set $set): array
    {
        //Alle Optionen als Array in Gruppen zurückgeben, die mit den Leiteigenschaften übereinstimmen.

        $koOptions = [
            'An meine Seite' => 'An meine Seite',
            'Aus dem Gleichgewicht' => 'Aus dem Gleichgewicht',
            'Aus der Deckung' => 'Aus der Deckung',
            'Block' => 'Block',
            'Durch den Hagel' => 'Durch den Hagel',
            'Entwaffnen' => 'Entwaffnen',
            'Katapult' => 'Katapult',
            'Kriegslärm' => 'Kriegslärm',
            'Kommando' => 'Kommando',
            'Notreserve' => 'Notreserve',
            'Ricochet' => 'Ricochet',
            'Schildschlag' => 'Schildschlag',
            'Schulterwurf' => 'Schulterwurf',
            'Sprengfalle' => 'Sprengfalle',
            ];
        $stOptions = [
            'Ansturm' => 'Ansturm',
            'Aufwühlen' => 'Aufwühlen',
            'Bieststärke' => 'Bieststärke',
            'Gegenangriff' => 'Gegenangriff',
            'Kraftvoller Wurf' => 'Kraftvoller Wurf',
            'Plattenbrecher' => 'Plattenbrecher',
            'Raserei' => 'Raserei',
            'Rücksichtslos' => 'Rücksichtslos',
            'Schädelbrecher' => 'Schädelbrecher',
            'Schmettern' => 'Schmettern',
            'Schwitzkasten' => 'Schwitzkasten',
            'Sprungangriff' => 'Sprungangriff',
            'Tausend Schläge' => 'Tausend Schläge',
            ];
        $agOptions =[
            'An die Kehle' => 'An die Kehle',
            'Ausweiden' => 'Ausweiden',
            'Durchbruch' => 'Durchbruch',
            'Entwaffnen' => 'Entwaffnen',
            'Heranziehen' => 'Heranziehen',
            'Klingentanz' => 'Klingentanz',
            'Klingenwirbel' => 'Klingenwirbel',
            'Reflektion' => 'Reflektion',
            'Rüstung zerreißen' => 'Rüstung zerreißen',
            'Sehnenschnitt' => 'Sehnenschnitt',
            'Vorbereitung' => 'Vorbereitung',
            'Waffenmeister' => 'Waffenmeister',
            'Waffenschmuck' => 'Waffenschmuck',
            'Wirbelwind' => 'Wirbelwind',
            'Zwischen die Schuppen' => 'Zwischen die Schuppen',
            ];
        $geOptions = [
            'Arsenal' => 'Arsenal',
            'Auf Distanz halten' => 'Auf Distanz halten',
            'Binden' => 'Binden',
            'Entschwinden' => 'Entschwinden',
            'Fester Stand' => 'Fester Stand',
            'Festnageln' => 'Festnageln',
            'In die Augen' => 'In die Augen',
            'Meucheln' => 'Meucheln',
            'Mit dem Spitzen Ende' => 'Mit dem Spitzen Ende',
            'Platzieren' => 'Platzieren',
            'Präzise' => 'Präzise',
            'Riposte' => 'Riposte',
            'Sturmangriff' => 'Sturmangriff',
            'Taschenspieler' => 'Taschenspieler',
            ];

        $arrayGroup =[
            'KO' => $koOptions,
            'ST' => $stOptions,
            'AG' => $agOptions,
            'GE' => $geOptions,
        ];

        $lekey = [
            'leiteigenschaft1' => $get('leiteigenschaft1'),
            'leiteigenschaft2' => $get('leiteigenschaft2'),
        ];


        $result = [];
        foreach ($lekey as $wert) {
            if (array_key_exists($wert, $arrayGroup)) {
                $result[$wert] = $arrayGroup[$wert];
            }
        }

        //Falls classability 2 array enthält Grenzenloses Wissen dann alle Optionen zurückgeben:
        if (in_array('Grenzenloses Wissen', $get('classability2'))) {
            return $arrayGroup;
        }

    return $result;
    }
    protected static function getRacialTraitsEffects(Get $get, Set $set): array
    {
        return [
            'Fleischig' => [
                // LeP benötigt die Eigenschafts-Güte (EG), die wir später über $get() abrufen müssen.
                'LeP_mod' => $get('xp') / 2,
            ],
            'Stacheln' => [
                'tragkraft' => -1,
            ],
            'Panzer' => [
                // Rüstungsschutz-Bonus für bestimmte Schadensarten
                'RS_stumpf' => 2,
                'RS_schnitt' => 2,
                'RS_stich' => 2,
                'RS_elementar' => 2,
            ],
            'Vierbeiner' => [
                'nw_hwp' => $get('xp') + 6, // Natürliche Waffe EG + 6 (HwP für Erweiterungen!!)
                'GS' => 2,
            ],
            'Zierlich / Kleinwüchsig' => [
                'pVW' => 5, // Persönlicher Verteidigungswert
                'KO_max' => 8, // Ein Validierungs-Constraint oder Hinweis
            ],
            'Zweite Haut' => [
                'nr' => true, // Ein String-Flag
                'Rüstung_Constraint' => 'Keine andere Rüstung tragbar', // Ein Hinweis/Constraint
            ],
            // ... weitere Merkmale
        ];
    }

    protected static function getArchetype(?string $leiteigenschaft1, ?string $leiteigenschaft2): string
    {
        if (!$leiteigenschaft1 || !$leiteigenschaft2) {
            return 'Unbekannt';
        }
        $archetypeMap = [
            'KO-KO' => 'Koloss',
            'KO-ST' => 'Sappeur',
            'KO-AG' => 'Krieger',
            'KO-GE' => 'Gladiator',
            'KO-WE' => 'Hüter',
            'KO-IN' => 'Druide',
            'KO-MU' => 'Schreckensritter',
            'KO-CH' => 'Bewahrer',
            'ST-ST' => 'Barbar',
            'ST-AG' => 'Mönch',
            'ST-GE' => 'Monsterjäger',
            'ST-WE' => 'Templer',
            'ST-IN' => 'Schamane',
            'ST-MU' => 'Berserker',
            'ST-CH' => 'Paladin',
            'AG-AG' => 'Klingenmeister',
            'AG-GE' => 'Duellant',
            'AG-WE' => 'Späher',
            'AG-IN' => 'Waldläufer',
            'AG-MU' => 'Assassine',
            'AG-CH' => 'Tänzer',
            'GE-GE' => 'Fechter',
            'GE-WE' => 'Runenschnitzer',
            'GE-IN' => 'Jäger',
            'GE-MU' => 'Pirscher',
            'GE-CH' => 'Barde',
            'WE-WE' => 'Mystiker',
            'WE-IN' => 'Magus',
            'WE-MU' => 'Thaumaturg',
            'WE-CH' => 'Seher',
            'IN-IN' => 'Elementarist',
            'IN-MU' => 'Nekromant',
            'IN-CH' => 'Animist',
            'MU-MU' => 'Hexer',
            'MU-CH' => 'Okkultist',
            'CH-CH' => 'Spiritualist',
        ];
        // Schlüssel in gleicher Reihenfolge erzeugen
        $key1 = "$leiteigenschaft1-$leiteigenschaft2";
        $key2 = "$leiteigenschaft2-$leiteigenschaft1"; // Falls Reihenfolge umgekehrt eingegeben wurde

        return $archetypeMap[$key1] ?? $archetypeMap[$key2] ?? 'Unbekannt';
    }

    public static function getAttributeArray($get): array
    {
        $AttributeArray = [
            'KO' => $get('ko'),
            'ST' => $get('st'),
            'AG' => $get('ag'),
            'GE' => $get('ge'),
            'WE' => $get('we'),
            'IN' => $get('in'),
            'MU' => $get('mu'),
            'CH' => $get('ch'),
        ];
        return $AttributeArray;
    }
    public static function getResources(?int $ko_toggle, ?string $leiteigenschaft1, ?string $leiteigenschaft2, ?array $data, ?int $xpdata): int
    {
        $value1 = isset($data[$leiteigenschaft1]) ? (int)$data[$leiteigenschaft1]: 0;
        $value2 = isset($data[$leiteigenschaft2]) ? (int)$data[$leiteigenschaft2]: 0;

        if ($leiteigenschaft1 === $leiteigenschaft2) {
            $mainstatevalue = $value1 * 3 + $xpdata;
            if (($leiteigenschaft1 === 'KO') && $ko_toggle) {
                $mainstatevalue = $value1 + $value2 + $xpdata - $data['KO'] * 2;
            }
        } else {
            if (($leiteigenschaft1 === 'KO' || $leiteigenschaft2 === 'KO') && $ko_toggle) {
                $mainstatevalue = $value1 + $value2 + $xpdata - $data['KO'];
            }
            else {
                $mainstatevalue = $value1 + $value2 + $xpdata;
            }
        }
        return $mainstatevalue;
    }

    public static function setMainStateValue(Get $get, Set $set): void
    {
        $set('main_stat_value',  self::getResources($get('ko_toggle'), $get('leiteigenschaft1'), $get('leiteigenschaft2'), self::getAttributeArray($get), $get('bonus_re')));
    }
    public static function BonusfromXp(Get $get, Set $set): int
    {

        $xp = $get('xp');
        $limit = match (true) {
            $xp >= 22 => 97,
            $xp >= 21 => 90,
            $xp >= 20 => 84,
            $xp >= 19 => 78,
            $xp >= 18 => 72,
            $xp >= 17 => 66,
            $xp >= 16 => 60,
            $xp >= 15 => 54,
            $xp >= 14 => 49,
            $xp >= 13 => 44,
            $xp >= 12 => 39,
            $xp >= 11 => 34,
            $xp >= 10 => 29,
            $xp >= 9 => 25,
            $xp >= 8 => 21,
            $xp >= 7 => 17,
            $xp >= 6 => 13,
            $xp >= 5 => 10,
            $xp >= 4 => 7,
            $xp >= 3 => 4,
            $xp >= 2 => 2,
            $xp >= 1 => 0,

            default => 0,
        };
        return $limit;
    }
    public static function maxEigenschaften($get, $set) : array
    {
        // Liste aller Eigenschaftsfelder
        $fields = ['ko', 'st', 'ag', 'ge', 'we', 'in', 'mu', 'ch'];

        // Erlaubte Maximal-Summe berechnen
        $xp = $get('xp');
        $max = 95 + $xp;

        // Aktuelle Summe der Eigenschaften berechnen
        $sum = 0;
        foreach ($fields as $field) {
            $value = $get($field);
            $sum += $value;
        }
        $set('maxeig', $max);
        $set('sumeig', $sum);
        $limit = min($xp + 13, 22);

        // Falls Summe zu hoch ist → Warnung
//        if ($sum > $max) {
//            Notification::make()
//                ->title("Die Summe deiner Eigenschaften darf bei XP {$xp} maximal {$max} betragen. Aktuell: {$sum}.")
//                ->danger()
//                ->send();
//        }

        return [
            'maxeig' => $max,
            'sumeig' => $sum,
            'limit' => $limit,
        ];
    }

    public static function calculateLeps(Get $get, Set $set): void
    {

        $ko = $get('ko');
        $xp = $get('bonus_lep');
        $le1 = $get('leiteigenschaft1');
        $le2 = $get('leiteigenschaft2');
        $ko_toggle = $get('ko_toggle');

        if ($ko_toggle) {
            if ($le1 === 'KO' && $le2 === 'KO') {
                $set('leps', $ko * 5 + $xp);
                $set('ko_bonus', $ko * 4);
            } elseif ($le1 === 'KO' || $le2 === 'KO') {
                $set('leps', $ko * 3 + $xp);
                $set('ko_bonus', $ko * 2);
            } else {
                $set('leps', $ko * 2 + $xp);
                $set('ko_bonus', $ko);

            }
        } else {
            $set('leps', $ko * 2 + $xp);
            $set('ko_bonus', $ko);

        }

    }
    public static function limitcraftability($get, $set): int
    {
        $xp = $get('xp');

//        if (in_array('Esoterische Kunst',$get('classability2'))) {
//            $limit = match (true) {
//                $xp >= 4 => 4,
//                $xp >= 2 => 3,
//                default => 2,
//            };
//        }
//        else
            $limit = match (true) {
                $xp >= 22 => 7,
                $xp >= 16 => 6,
                $xp >= 11 => 5,
                $xp >= 7 => 4,
                $xp >= 4 => 3,
                $xp >= 2 => 2,
                default => 1,
            };


        if (is_array($get('handwerkskenntnisse')) && count($get('handwerkskenntnisse')) > $limit) {
            $set('handwerkskenntnisse', array_slice($get('handwerkskenntnisse'), 0, $limit));
        }
        return $limit;
    }

    public static function limitskills ($get)
    {
        $skillFields = ['skill_weapon', 'skill_aspect',];
        $xp = $get('xp');
        $limit = match (true) {
            $xp >= 20=> 14,
            $xp >= 19=> 13,
            $xp >= 18=> 12,
            $xp >= 15=> 11,
            $xp >= 14=> 10,
            $xp >= 12=> 9,
            $xp >= 10=> 8,
            $xp >= 8=> 7,
            $xp >= 6=> 6,
            $xp >= 5=> 5,
            $xp >= 3=> 4,
            default   => 3,
        };


        $classabilityFields = [
            'classability1',
            'classability2',
            'classability3',
        ];

        $eingebungCount = collect($classabilityFields)
            ->map(fn ($field) => $get($field) ?? [])
            ->flatten()
            ->filter(fn ($ability) =>
            is_string($ability)
                ? $ability === 'Eingebung'
                : ($ability['name'] ?? null) === 'Eingebung'
            )
            ->count();

        // Alle ausgewählten Skills zusammenzählen
        $allSkills = [];
        foreach ($skillFields as $field) {
            $values = $get($field);
            if (is_array($values)) {
                $allSkills[$field] = $values;
            } else {
                $allSkills[$field] = [];
            }
        }
        $flatList = array_merge(...array_values($allSkills));
        return [
            'flatList' => $flatList,
            'limit' => $limit,
        ];
    }

    public static function setAttributeBonus($state, Get $get, Set $set): void
    {
        $boni = $get('boni') ?? [];

        // Basiswerte zurücksetzen, bevor neu berechnet wird
        $baseAttributes = ['ko', 'st', 'ag', 'ge', 'we', 'in', 'mu', 'ch'];
        foreach ($baseAttributes as $attr) {
            $baseValue = $get($attr) ?? 0;
            $set("{$attr}_sum", $baseValue);
            $set("{$attr}_max", $get("{$attr}_max_base") ?? 10);
        }

        $baseTalente = [
            'Zähigkeit', 'Kraftakt', 'Körperbeherrschung', 'Fingerfertigkeit',
            'Konzentration', 'Wahrnehmung', 'Willenskraft', 'Kommunikation'
        ];

        // Schleife durch alle vergebenen Boni
        foreach ($boni as $bonus) {
            $selected = $bonus['bonus'] ?? null;
            if (!$selected) continue;

            // Eigenschaftsbonus
            if (in_array($selected, $baseAttributes, true)) {
                $current = $get("{$selected}_sum") ?? 0;
                $set("{$selected}_sum", $current + 1);

                $max = $get("{$selected}_max") ?? 0;
                $set("{$selected}_max", $max + 1);
            }

            // Basistalentbonus
            if (in_array($selected, $baseTalente, true)) {
                $key = strtolower(str_replace('.', '', $selected));
                $current = $get("{$key}_sum") ?? 0;
                $set("{$key}_sum", $current + 4);
            }
        }
    }

}
