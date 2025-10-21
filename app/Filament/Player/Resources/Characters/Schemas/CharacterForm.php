<?php

namespace App\Filament\Player\Resources\Characters\Schemas;

use App\Filament\Resources\EquipmentResource;
use App\Models\Equipment;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class CharacterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->statePath('')
                    ->tabs([
                        Tabs\Tab::make('Name und Rasse')
                            ->schema([
                                Section::make('Name Erfahrungsgraf und Beschreibung')
                                    ->description('Name Erfahrungsgraf und Beschreibung auswählen')
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('name')
                                                    ->label('Name')
                                                    ->required()
                                                    ->maxLength(255),
                                                TextInput::make('xp')
                                                    ->Label('Erfahrungsstufe')
                                                    ->numeric()
                                                    ->default(1)
                                                    ->live(onBlur: true)
                                                    ->step(1)
                                                    ->maxValue(22)
                                                    ->required()
                                                    ->minValue(1)
                                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                        $set('leps', $get('ko') * 2 + $state);
                                                        $set('seelenpunkte', $get('ch') * 2 + $get('xp'));
                                                        $set('initiative', round($get('in') / 2 + $get('xp')));
                                                        self::setMainStateValue($get, $set);
                                                        self::maxEigenschaften($get, $set);
                                                    })
                                                    ->reactive(),
                                            ]),
                                        Textarea::make('description')
                                            ->label('Description')
                                            ->maxLength(800),
                                    ]),
                                Section::make('Rasse und Rassenmerkmale')
                                    ->description('Rasse und Rassenmerkmale auswählen')
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
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
                                                Radio::make('wesen')
                                                    ->required()
                                                    ->options([
                                                        'Biest' => 'Biest/Geist',
                                                        'Dämon' => 'Dämon/Spekter',
                                                    ]),
                                                Select::make('rassenmerkmale')
                                                    ->multiple(3)
                                                    ->options([
                                                        'Apex' => 'Apex',
                                                        'Balzkleid' => 'Balzkleid',
                                                        'Beutetier' => 'Beutetier',
                                                        'Eingefettet' => 'Eingefettet',
                                                        'Fettpolster' => 'Fettpolster',
                                                        'Fleischig' => 'Fleischig',
                                                        'Geschuppt' => 'Geschuppt',
                                                        'Giftig' => 'Giftig',
                                                        'Glitschig' => 'Glitschig',
                                                        'Kiemen' => 'Kiemen',
                                                        'Medium' => 'Medium',
                                                        'Nachtsicht' => 'Nachtsicht',
                                                        'Nackt' => 'Nackt',
                                                        'Panzer' => 'Panzer',
                                                        'Photosynthese' => 'Photosynthese',
                                                        'Raubtier/Hörner' => 'Raubtier/Hörner',
                                                        'Reittier' => 'Reittier',
                                                        'Samtpfote' => 'Samtpfote',
                                                        'Schleimspur' => 'Schleimspur',
                                                        'Schlinger' => 'Schlinger',
                                                        'Schwanz' => 'Schwanz',
                                                        'Schwingen' => 'Schwingen',
                                                        'Siebter Sinn' => 'Siebter Sinn',
                                                        'Spitzohr' => 'Spitzohr',
                                                        'Sprunggelenke' => 'Sprunggelenke',
                                                        'Spucker/Dornenkapseln' => 'Spucker/Dornenkapseln',
                                                        'Spürnase' => 'Spürnase',
                                                        'Stacheln' => 'Stacheln',
                                                        'Tarnmuster' => 'Tarnmuster',
                                                        'Tiefe Taschen' => 'Tiefe Taschen',
                                                        'Treibholz' => 'Treibholz',
                                                        'Unscheinbar' => 'Unscheinbar',
                                                        'Vielgliedrig' => 'Vielgliedrig',
                                                        'Vierbeiner' => 'Vierbeiner',
                                                        'Vital' => 'Vital',
                                                        'Zierlich/Kleinwüchsig' => 'Zierlich/Kleinwüchsig',
                                                    ]),
                                            ]),
                                    ]),
                            ]),
                        Tabs\Tab::make('Eigenschaften und Archetyp')
                            ->schema([
                                Section::make('Archetyp, Leiteigenschaften')
                                    ->description('Leiteigenschaften und Archetyp auswählen')
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Select::make('leiteigenschaft1')
                                                    ->label('Leiteigenschaft 1')
                                                    ->required()
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
                                                    ->reactive()
                                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                        $set('archetype', self::getArchetype($state, $get('leiteigenschaft2')));
                                                        self::setMainStateValue($get, $set);
                                                        self::calculateLeps($get, $set);
                                                    })
                                                    ->afterStateHydrated(function ($state, Get $get, Set $set) {
                                                        $set('archetype', self::getArchetype($state, $get('leiteigenschaft2')));   //sets archetype
                                                        self::setMainStateValue($get, $set);
                                                        self::calculateLeps($get, $set);
                                                    }),
                                                Select::make('leiteigenschaft2')
                                                    ->label('Leiteigenschaft 2')
                                                    ->required()
                                                    ->options([
                                                        // '-' => '-',
                                                        'KO' => 'Konstitution',
                                                        'ST' => 'Stärke',
                                                        'AG' => 'Agilität',
                                                        'GE' => 'Geschick',
                                                        'WE' => 'Weisheit',
                                                        'IN' => 'Intuition',
                                                        'MU' => 'Mut',
                                                        'CH' => 'Charisma',
                                                    ])
                                                    ->reactive()
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
                                            ]),
                                        Grid::make(3)
                                            ->schema([
                                                TextInput::make('archetype')
                                                    ->label('Archetyp')
                                                    ->live()
                                                    ->default(fn (callable $get) => self::getArchetype($get('leiteigenschaft1'), $get('leiteigenschaft2')))
                                                    ->disabled(),

                                                TextInput::make('main_stat_value')
                                                    ->label('Ressourcen')
                                                    ->live()
                                                    ->disabled()
                                                    ->dehydrated(),
                                                Toggle::make('ko_toggle')
                                                    ->label('KO für LeP verwenden')
                                                    ->reactive()
                                                    ->inline(false)
                                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                        self::setMainStateValue($get, $set);
                                                        self::calculateLeps($get, $set);

                                                    })
                                                    ->reactive(),
                                            ])
                                    ]),
                                Section::make('Eigenschaften')
                                    ->description(function (Get $get, Set $set) {
                                        $result = self::maxEigenschaften($get, $set);

                                        return "{$result['sumeig']}   von {$result['maxeig']} Punkten vergeben. Maximal {$result['limit']} pro Eigenschaft.";
                                    })
//                                    ->columns([
//                                        'lg' => 2,
//                                        'sm' => 1,
//                                    ])
                                    ->collapsible()
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
                                                        return $get('limit') ?? 100;;
                                                    })
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                        self::calculateLeps($get, $set);
                                                        self::maxEigenschaften($get, $set);
                                                        self::setMainStateValue($get, $set);
                                                    })
                                                    ->required(),
                                                TextInput::make('st') // Stärke
                                                ->label('Stärke (ST)')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                        $set('tragkraft', $state);
                                                        self::maxEigenschaften($get, $set);
                                                        self::setMainStateValue($get, $set);
                                                    })
                                                    ->required(),
                                                TextInput::make('ag') // Agilität
                                                ->label('Agilität (AG)')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                        $set('geschwindigkeit', round($state / 2));
                                                        self::maxEigenschaften($get, $set);
                                                        self::setMainStateValue($get, $set);
                                                    })
                                                    ->required(),
                                                TextInput::make('ge') // Geschick
                                                ->label('Geschick (GE)')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                        $set('handwerksbonus', $state - 12);
                                                        self::maxEigenschaften($get, $set);
                                                        self::setMainStateValue($get, $set);
                                                    })
                                                    ->required(),
                                                TextInput::make('we') // Weisheit
                                                ->label('Weisheit (WE)')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                        $set('kontrollwiderstand', $state - 12);
                                                        self::maxEigenschaften($get, $set);
                                                        self::setMainStateValue($get, $set);
                                                    })
                                                    ->required(),
                                                TextInput::make('in') // Instinkt
                                                ->label('Instinkt (IN)')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                        $set('initiative', round($state / 2 + $get('xp')));
                                                        self::maxEigenschaften($get, $set);
                                                        self::setMainStateValue($get, $set);
                                                    })
                                                    ->required(),
                                                TextInput::make('mu') // Mut
                                                ->label('Mut (MU)')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                        $set('verteidigung', $state - 12);
                                                        self::maxEigenschaften($get, $set);
                                                        self::setMainStateValue($get, $set);
                                                    })
                                                    ->required(),
                                                TextInput::make('ch') // Charisma
                                                ->label('Charisma (CH)')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                        $set('seelenpunkte', $state * 2 + $get('xp'));
                                                        self::setMainStateValue($get, $set);
                                                        self::maxEigenschaften($get, $set);
                                                    })
                                                    ->required(),
                                            ]),
                                    ]),
                                Section::make('Berechnete Werte')
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(4)
                                            ->schema([
                                                TextInput::make('leps') // Lebenspunkte
                                                ->label('Lebenspunkte (LeP)')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                TextInput::make('tragkraft') // Tragkraft
                                                ->label('Tragkraft')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                TextInput::make('geschwindigkeit') // Geschwindigkeit
                                                ->label('Geschwindigkeit')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                TextInput::make('handwerksbonus') // Handwerksbonus
                                                ->label('Handwerksbonus')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                TextInput::make('kontrollwiderstand') // Kontrollwiderstand
                                                ->label('Kontrollwiderstand')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                TextInput::make('initiative') // Initiative
                                                ->label('Initiative (Ini)')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                TextInput::make('verteidigung') // Verteidigung
                                                ->label('Verteidigung')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                TextInput::make('seelenpunkte') // Seelenpunkte
                                                ->label('Seelenpunkte (SeP)')
                                                    ->disabled()
                                                    ->dehydrated(),
                                            ])
                                    ])
                            ]),
                        Tabs\Tab::make('Fertigkeiten')
                            ->schema([
                                Section::make('Klassenfertigkeiten, Handwerkskenntnis, Überlieferungen')
                                    ->description('Klassenfertigkeiten, Handwerkskenntnis und Überlieferungen auswählen')
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('classability1')
                                                    ->label('Klassenfertigkeiten I')
                                                    ->multiple()
                                                    ->live()
                                                    ->options([
                                                        'An Leibern laben' => 'An Leibern laben',
                                                        'Auf der Lauer' => 'Auf der Lauer',
                                                        'Aura lesen' => 'Aura lesen',
                                                        'Aus dem Nichts' => 'Aus dem Nichts',
                                                        'Bewegungsmuster' => 'Bewegungsmuster',
                                                        'Elementare Essenz - wähle zwei' => 'Elementare Essenz - wähle zwei',
                                                        'Empathie' => 'Empathie',
                                                        'Erneuerung' => 'Erneuerung',
                                                        'Fahler Schleier' => 'Fahler Schleier',
                                                        'Fallensteller' => 'Fallensteller',
                                                        'Fluchwirker' => 'Fluchwirker',
                                                        'Furchtlos' => 'Furchtlos',
                                                        'Gabelung im Fluss' => 'Gabelung im Fluss',
                                                        'Gaben des Tempels' => 'Gaben des Tempels',
                                                        'Ganz selbstverständlich' => 'Ganz selbstverständlich',
                                                        'Gebildet' => 'Gebildet',
                                                        'Geschärfte Klingen' => 'Geschärfte Klingen',
                                                        'Gestählter Wille' => 'Gestählter Wille',
                                                        'Geweihter Hammer' => 'Geweihter Hammer',
                                                        'Gute Gene' => 'Gute Gene',
                                                        'In Stellung' => 'In Stellung',
                                                        'Jenseitiger Vermittler' => 'Jenseitiger Vermittler',
                                                        'Kernschuss' => 'Kernschuss',
                                                        'Kosmische Durchdringung' => 'Kosmische Durchdringung',
                                                        'Laute Stimme' => 'Laute Stimme',
                                                        'Lautlos' => 'Lautlos',
                                                        'Leide!' => 'Leide!',
                                                        'Machtvoller Wille' => 'Machtvoller Wille',
                                                        'Massaker' => 'Massaker',
                                                        'Medium' => 'Medium',
                                                        'Mit Schwung' => 'Mit Schwung',
                                                        'Offene Pforten' => 'Offene Pforten',
                                                        'Opportunist' => 'Opportunist',
                                                        'Randnotizen' => 'Randnotizen',
                                                        'Ruf des Vertrauten' => 'Ruf des Vertrauten',
                                                        'Runenschmuck' => 'Runenschmuck',
                                                        'Schöne Augen' => 'Schöne Augen',
                                                        'Stets Wachsam' => 'Stets Wachsam',
                                                        'Synchronreflex' => 'Synchronreflex',
                                                        'Taktischer Rückzug' => 'Taktischer Rückzug',
                                                        'Talentiert' => 'Talentiert',
                                                        'Tierflüsterer' => 'Tierflüsterer',
                                                        'Totem' => 'Totem',
                                                        'Treuer Weggefährte' => 'Treuer Weggefährte',
                                                        'Unleben' => 'Unleben',
                                                        'Unnötiger Balast' => 'Unnötiger Balast',
                                                        'Unter meinem Schutz' => 'Unter meinem Schutz',
                                                        'Verbrannte Erde' => 'Verbrannte Erde',
                                                        'Versatiler Kampfstil' => 'Versatiler Kampfstil',
                                                        'Verzerrter Schleier' => 'Verzerrter Schleier',
                                                        'Vorbereitung' => 'Vorbereitung',
                                                        'Weitgereist' => 'Weitgereist',
                                                        'Wer austeilt' => 'Wer austeilt',
                                                        'Wut' => 'Wut',
                                                        'Zur Deckung' => 'Zur Deckung',
                                                        'Zwischen den Bäumen' => 'Zwischen den Bäumen',
                                                        ])
                                                    ->afterstateUpdated(function (Get $get, Set $set) {
                                                        self::limitclassability1($get, $set);
                                                    })
                                                    ->hint(function (Get $get, Set $set) {
                                                        $value = count($get('classability1')) ?? 10;
                                                        $limit = self::limitclassability1($get, $set);
                                                        return "{$value} von {$limit}";
                                                    }),
                                                Select::make('classability2')
                                                    ->label('Klassenfertigkeiten II')
                                                    ->multiple()
                                                    ->live()
                                                    ->options([                                                        'Ablenkungsmanöver' => 'Ablenkungsmanöver',
                                                        'Adlerauge' => 'Adlerauge',
                                                        'Adrenalin' => 'Adrenalin',
                                                        'Arm der GGötter' => 'Arm der GGötter',
                                                        'Armee der Toten' => 'Armee der Toten',
                                                        'Auf der Tonspur' => 'Auf der Tonspur',
                                                        'Aus dem Ärmel' => 'Aus dem Ärmel',
                                                        'Aus dem besten Holz geschnitzt' => 'Aus dem besten Holz geschnitzt',
                                                        'Aus dem Handgelenk' => 'Aus dem Handgelenk',
                                                        'Austauschbar' => 'Austauschbar',
                                                        'Bewusstes Risiko' => 'Bewusstes Risiko',
                                                        'Blick dahinter' => 'Blick dahinter',
                                                        'Blitzschnell' => 'Blitzschnell',
                                                        'Blutmagie' => 'Blutmagie',
                                                        'Bollwerk' => 'Bollwerk',
                                                        'Borke' => 'Borke',
                                                        'Das letzte Wort' => 'Das letzte Wort',
                                                        'Destruktive Ader' => 'Destruktive Ader',
                                                        'Einklang' => 'Einklang',
                                                        'En Garde' => 'En Garde',
                                                        'Experimente' => 'Experimente',
                                                        'Faust der Elemente' => 'Faust der Elemente',
                                                        'Für den Kampf geschaffen' => 'Für den Kampf geschaffen',
                                                        'Gebundene Gnade' => 'Gebundene Gnade',
                                                        'Gesplitterte Bindung' => 'Gesplitterte Bindung',
                                                        'Gewusst wie' => 'Gewusst wie',
                                                        'Gnadenlos' => 'Gnadenlos',
                                                        'Grenzenloses Wissen' => 'Grenzenloses Wissen',
                                                        'Gute Seele' => 'Gute Seele',
                                                        'Heiliger Zorn' => 'Heiliger Zorn',
                                                        'Hinter dem Vorhang' => 'Hinter dem Vorhang',
                                                        'Kampfgeschirr' => 'Kampfgeschirr',
                                                        'Karmale Barriere' => 'Karmale Barriere',
                                                        'Kaskade' => 'Kaskade',
                                                        'Kettenreaktion' => 'Kettenreaktion',
                                                        'Koordinierter Angriff' => 'Koordinierter Angriff',
                                                        'Kreuzblock' => 'Kreuzblock',
                                                        'Kundiger Führer' => 'Kundiger Führer',
                                                        'Lebensentzug' => 'Lebensentzug',
                                                        'Machtvolle Runen' => 'Machtvolle Runen',
                                                        'Memorierte Riten' => 'Memorierte Riten',
                                                        'Mit dem flachen Ende' => 'Mit dem flachen Ende',
                                                        'Mit dem Wind' => 'Mit dem Wind',
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
                                                        'Synergetik' => 'Synergetik',
                                                        'Tanzbein' => 'Tanzbein',
                                                        'Tausend Klingen' => 'Tausend Klingen',
                                                        'Unbemerkt' => 'Unbemerkt',
                                                        'Unerschöpflich' => 'Unerschöpflich',
                                                        'Volle Kontrolle' => 'Volle Kontrolle',
                                                        'Von allen Seiten' => 'Von allen Seiten',
                                                        'Wandelndes Land' => 'Wandelndes Land',
                                                        'Alles oder nichts' => 'Alles oder nichts',
                                                        'Avatar' => 'Avatar',
                                                        'Blutsbruderschaft' => 'Blutsbruderschaft',
                                                        'Flèche' => 'Flèche',
                                                        'Fluss des Kosmos' => 'Fluss des Kosmos',
                                                        'Gestaltwandler' => 'Gestaltwandler',
                                                        'Herr über den Verstand' => 'Herr über den Verstand',
                                                        'Lohn der Gläubigen' => 'Lohn der Gläubigen',
                                                        'Meuchler' => 'Meuchler',
                                                        'Reflektierter Geist' => 'Reflektierter Geist',
                                                        'Sekundenbruchteil' => 'Sekundenbruchteil',
                                                        'Ultima' => 'Ultima',
                                                        'Urteil der Arena' => 'Urteil der Arena',
                                                        'Zwischen die Schuppen' => 'Zwischen die Schuppen',
                                                    ])
                                                    ->afterstateUpdated(function (Get $get, Set $set) {
                                                        self::limitclassability2($get, $set);
                                                    })
                                                    ->hint(function (Get $get, Set $set) {
                                                        $value = count($get('classability2')) ?? 10;
                                                        $limit = self::limitclassability2($get, $set);
                                                        return "{$value} von {$limit}";
                                                    }),
                                                Select::make('classability3')
                                                    ->label('Klassenfertigkeiten III')
                                                    ->multiple()
                                                    ->live()
                                                    ->options([
                                                        'Alles oder nichts' => 'Alles oder nichts',
                                                        'Avatar' => 'Avatar',
                                                        'Blutsbruderschaft' => 'Blutsbruderschaft',
                                                        'Flèche' => 'Flèche',
                                                        'Fluss des Kosmos' => 'Fluss des Kosmos',
                                                        'Gestaltwandler' => 'Gestaltwandler',
                                                        'Herr über den Verstand' => 'Herr über den Verstand',
                                                        'Lohn der Gläubigen' => 'Lohn der Gläubigen',
                                                        'Meuchler' => 'Meuchler',
                                                        'Reflektierter Geist' => 'Reflektierter Geist',
                                                        'Sekundenbruchteil' => 'Sekundenbruchteil',
                                                        'Ultima' => 'Ultima',
                                                        'Urteil der Arena' => 'Urteil der Arena',
                                                        'Zwischen die Schuppen' => 'Zwischen die Schuppen',
                                                    ])
                                                    ->afterstateUpdated(function (Get $get, Set $set) {
                                                        self::limitclassability3($get, $set);
                                                    })
                                                    ->hint(function (Get $get, Set $set) {
                                                        $value = count($get('classability3')) ?? 10;
                                                        $limit = self::limitclassability3($get, $set);
                                                        return "{$value} von {$limit}";
                                                    }),
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
                                                        $limit = self::limithandwerk($get, $set);
                                                        return "{$value} von {$limit}";
                                                    }),
                                                Select::make('lore')
                                                    ->label('Überlieferungen')
                                                    ->multiple()
                                                    ->live()
                                                    ->options([
                                                        'Aspektwesen' => 'Aspektwesen',
                                                        'Fauna & Flora' => 'Fauna & Flora',
                                                        'Götter' => 'Götter',
                                                        'Monster' => 'Monster',
                                                        'Seelen' => 'Seelen',
                                                        'Varculac' => 'Varculac',
                                                        'Länder des Nordens' => 'Länder des Nordens',
                                                        'Länder des Südens' => 'Länder des Südens',
                                                        'Spiegelwelt' => 'Spiegelwelt',
                                                        'Splitterwelt' => 'Splitterwelt',
                                                        'Unterwelt' => 'Unterwelt',
                                                        'Völker des Nordens' => 'Völker des Nordens',
                                                        'Völker des Südens' => 'Völker des Südens',
                                                    ]),
                                            ]),
                                    ]),
                                Section::make('Fertigkeiten')
                                    ->description(function (Get $get, Set $set) {
                                        $result = self::limitskills($get, $set);
                                        return  count($result['flatList']). ' von ' .$result['limit']. ' Aspekt- und Waffenfertigkeiten ausgewählt';
                                    })
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(4)
                                            ->schema([
                                                Select::make('skill_ko')
                                                    ->hint(function (Get $get, Set $set) {
                                                        $result = self::limitskills($get, $set);
                                                        return count($get('skill_ko')) . ' von insgesamt ' . $result['limit'];
                                                    })
                                                    ->multiple()
                                                    ->live()
                                                    ->options([
                                                        'Block' => 'Block',
                                                        'Aus der Deckung' => 'Aus der Deckung',
                                                        'Entwaffnen' => 'Entwaffnen',
                                                        'Schildschlag' => 'Schildschlag',
                                                        'Durch den Hagel' => 'Durch den Hagel',
                                                        'Sprengfalle' => 'Sprengfalle',
                                                        'Notreserve' => 'Notreserve',
                                                        'Ricochet' => 'Ricochet',
                                                        'Aus dem Gleichgewicht' => 'Aus dem Gleichgewicht',
                                                        'Schulterwurf' => 'Schulterwurf',
                                                        'Katapult' => 'Katapult',
                                                        'An meine Seite' => 'An meine Seite',
                                                        'Kommando' => 'Kommando',
                                                        'Kriegslärm' => 'Kriegslärm',
                                                        'Aus der Not' => 'Aus der Not',
                                                    ])
                                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                                        self::limitskills($get, $set);
                                                    })
                                                    ->disabled(function ($state, Get $get) {
                                                        if ($get('leiteigenschaft1') === 'KO' || $get('leiteigenschaft2') === 'KO') {
                                                            return false;
                                                        }
                                                        return true;
                                                    }),
                                                Select::make('skill_st')
                                                    ->hint(function (Get $get, Set $set) {
                                                        $result = self::limitskills($get, $set);
                                                        return count($get('skill_st')) . ' von insgesamt ' . $result['limit'];
                                                    })
                                                    ->multiple()
                                                    ->live()
                                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                                        self::limitskills($get, $set);
                                                    })
                                                    ->disabled(function ($state, Get $get) {
                                                        return self::isSkillactive($get, 'ST');
                                                    })
                                                    ->options([
                                                        'Plattenbrecher' => 'Plattenbrecher',
                                                        'Schädelbrecher' => 'Schädelbrecher',
                                                        'Tausend Schläge' => 'Tausend Schläge',
                                                        'Schmettern' => 'Schmettern',
                                                        'Ansturm' => 'Ansturm',
                                                        'Schwitzkasten' => 'Schwitzkasten',
                                                        'Sprungangriff' => 'Sprungangriff',
                                                        'Bieststärke' => 'Bieststärke',
                                                        'Gegenangriff' => 'Gegenangriff',
                                                        'Rücksichtslos' => 'Rücksichtslos',
                                                        'Aufwühlen' => 'Aufwühlen',
                                                        'Raserei' => 'Raserei',
                                                        'Kraftvoller Wurf' => 'Kraftvoller Wurf',
                                                    ]),
                                                Select::make('skill_ag')
                                                    ->hint(function (Get $get, Set $set) {
                                                        $result = self::limitskills($get, $set);
                                                        return count($get('skill_ag')) . ' von insgesamt ' . $result['limit'];
                                                    })
                                                    ->multiple()
                                                    ->live()
                                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                                        self::limitskills($get, $set);
                                                    })
                                                    ->disabled(function ($state, Get $get) {
                                                        return self::isSkillactive($get, 'AG');
                                                    })
                                                    ->options([
                                                        'Ausweiden' => 'Ausweiden',
                                                        'Rüstung zerreißen' => 'Rüstung zerreißen',
                                                        'Wirbelwind' => 'Wirbelwind',
                                                        'Waffenmeister' => 'Waffenmeister',
                                                        'Vorbereitung' => 'Vorbereitung',
                                                        'An die Kehle' => 'An die Kehle',
                                                        'Sehnenschnitt' => 'Sehnenschnitt',
                                                        'Durchbruch' => 'Durchbruch',
                                                        'Klingentanz' => 'Klingentanz',
                                                        'Heranziehen' => 'Heranziehen',
                                                        'Klingenwirbel' => 'Klingenwirbel',
                                                        'Zwischen die Schuppen' => 'Zwischen die Schuppen',
                                                        'Entwaffnen' => 'Entwaffnen',
                                                        'Reflektion' => 'Reflektion',
                                                        'Waffenschmuck' => 'Waffenschmuck',
                                                    ]),
                                                Select::make('skill_ge')
                                                    ->hint(function (Get $get, Set $set) {
                                                        $result = self::limitskills($get, $set);
                                                        return count($get('skill_ge')) . ' von insgesamt ' . $result['limit'];
                                                    })
                                                    ->multiple()
                                                    ->live()
                                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                                        self::limitskills($get, $set);
                                                    })
                                                    ->disabled(function ($state, Get $get) {
                                                        return self::isSkillactive($get, 'GE');
                                                    })
                                                    ->options([
                                                        'Meucheln' => 'Meucheln',
                                                        'Mit dem Spitzen Ende' => 'Mit dem Spitzen Ende',
                                                        'Präzise' => 'Präzise',
                                                        'Taschenspieler' => 'Taschenspieler',
                                                        'In die Augen' => 'In die Augen',
                                                        'Auf Distanz halten' => 'Auf Distanz halten',
                                                        'Binden' => 'Binden',
                                                        'Sturmangriff' => 'Sturmangriff',
                                                        'Entschwinden' => 'Entschwinden',
                                                        'Festnageln' => 'Festnageln',
                                                        'Fester Stand' => 'Fester Stand',
                                                        'Arsenal' => 'Arsenal',
                                                        'Platzieren' => 'Platzieren',
                                                        'Riposte' => 'Riposte',
                                                    ]),
                                                Select::make('skill_in')
                                                    ->hint(function (Get $get, Set $set) {
                                                        $result = self::limitskills($get, $set);
                                                        return count($get('skill_in')) . ' von insgesamt ' . $result['limit'];
                                                    })
                                                    ->multiple()
                                                    ->live()
                                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                                        self::limitskills($get, $set);
                                                    })
                                                    ->disabled(function ($state, Get $get) {
                                                        return self::isSkillactive($get, 'IN');
                                                    })
                                                    ->options([
                                                        'Illuminos' => 'Illuminos',
                                                        'Spiri Exvocare' => 'Spiri Exvocare',
                                                        'Soliri' => 'Soliri',
                                                        'Lux Columna' => 'Lux Columna',
                                                        'Purgato' => 'Purgato',
                                                        'Oculux' => 'Oculux',
                                                        'Calefaciendo' => 'Calefaciendo',
                                                        'Anhelitus' => 'Anhelitus',
                                                        'Intu' => 'Intu',
                                                        'Volaris' => 'Volaris',
                                                        'Liberare' => 'Liberare',
                                                        'Sonarus' => 'Sonarus',
                                                        'Ambulaqua' => 'Ambulaqua',
                                                        'Caligos' => 'Caligos',
                                                        'Mollis' => 'Mollis',
                                                        'Pundio' => 'Pundio',
                                                        'Sitis' => 'Sitis',
                                                        'Tempestare' => 'Tempestare',
                                                        'Siccatio' => 'Siccatio',
                                                        'Quaestio Elementi' => 'Quaestio Elementi',
                                                        'Crystaspino' => 'Crystaspino',
                                                        'Fricarcer' => 'Fricarcer',
                                                        'Calyx' => 'Calyx',
                                                        'Pellucidus' => 'Pellucidus',
                                                        'Frigtreus' => 'Frigtreus',
                                                        'Convertempa' => 'Convertempa',
                                                        'Praeterivide' => 'Praeterivide',
                                                        'Tardius' => 'Tardius',
                                                        'Posultempa' => 'Posultempa',
                                                        'Divinatio' => 'Divinatio',
                                                        'Furtim' => 'Furtim',
                                                        'Sano' => 'Sano',
                                                        'Corpus Mutare' => 'Corpus Mutare',
                                                        'Dumus' => 'Dumus',
                                                        'Vocatus Pral' => 'Vocatus Pral',
                                                        'Vocatus Bestia' => 'Vocatus Bestia',
                                                        'Caminus' => 'Caminus',
                                                        'Arsitis' => 'Arsitis',
                                                        'Circuligne' => 'Circuligne',
                                                        'Ahenum' => 'Ahenum',
                                                        'Incendium' => 'Incendium',
                                                        'Gravis' => 'Gravis',
                                                        'Terra Motus' => 'Terra Motus',
                                                        'Magnes' => 'Magnes',
                                                        'Terra Sculpta' => 'Terra Sculpta',
                                                        'Corpus Lapis' => 'Corpus Lapis',
                                                    ]),
                                                Select::make('skill_we')
                                                    ->hint(function (Get $get, Set $set) {
                                                        $result = self::limitskills($get, $set);
                                                        return count($get('skill_we')) . ' von insgesamt ' . $result['limit'];
                                                    })
                                                    ->multiple()
                                                    ->live()
                                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                                        self::limitskills($get, $set);
                                                    })
                                                    ->disabled(function ($state, Get $get) {
                                                        return self::isSkillactive($get, 'WE');
                                                    })
                                                    ->options([
                                                        'Iunctio' => 'Iunctio',
                                                        'Veto Umbrax' => 'Veto Umbrax',
                                                        'Vitae' => 'Vitae',
                                                        'Quaestio Arcana' => 'Quaestio Arcana',
                                                        'Effio Arcana' => 'Effio Arcana',
                                                        'Porta Speculum' => 'Porta Speculum',
                                                        'Forma Kinetia' => 'Forma Kinetia',
                                                        'Proiectum' => 'Proiectum',
                                                        'Pupa' => 'Pupa',
                                                        'Celero' => 'Celero',
                                                        'Ictos' => 'Ictos',
                                                        'Moveo' => 'Moveo',
                                                        'Corpus Morpha' => 'Corpus Morpha',
                                                        'Corpus Forma' => 'Corpus Forma',
                                                        'Forma Mutatio' => 'Forma Mutatio',
                                                        'Confirma' => 'Confirma',
                                                        'Erupit' => 'Erupit',
                                                        'Principor' => 'Principor',
                                                        'Collatio' => 'Collatio',
                                                        'Vexillum' => 'Vexillum',
                                                        'Auxillum' => 'Auxillum',
                                                        'Sucus Constantia' => 'Sucus Constantia',
                                                        'Exvocare Exterreo' => 'Exvocare Exterreo',
                                                        'Corpus Nox' => 'Corpus Nox',
                                                        'Perdita' => 'Perdita',
                                                        'Tenebra' => 'Tenebra',
                                                        'Maledictum' => 'Maledictum',
                                                        'Duplici' => 'Duplici',
                                                        'Fecundo' => 'Fecundo',
                                                        'Purus' => 'Purus',
                                                        'Curatio Morbus' => 'Curatio Morbus',
                                                        'Corpus Renovo' => 'Corpus Renovo',
                                                        'Corpus Cupla' => 'Corpus Cupla',
                                                        'Veritas' => 'Veritas',
                                                        'Lepos' => 'Lepos',
                                                        'Ligo Spiri' => 'Ligo Spiri',
                                                        'Pondus' => 'Pondus',
                                                        'Spiri Duro' => 'Spiri Duro',
                                                        'Vigil' => 'Vigil',
                                                        'Percello' => 'Percello',
                                                        'Custodia' => 'Custodia',
                                                    ]),
                                                Select::make('skill_mu')
                                                    ->hint(function (Get $get, Set $set) {
                                                        $result = self::limitskills($get, $set);
                                                        return count($get('skill_mu')) . ' von insgesamt ' . $result['limit'];
                                                    })
                                                    ->multiple()
                                                    ->live()
                                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                                        self::limitskills($get, $set);
                                                    })
                                                    ->disabled(function ($state, Get $get) {
                                                        return self::isSkillactive($get, 'MU');
                                                    })
                                                    ->options([
                                                        'Coactus' => 'Coactus',
                                                        'Veto Nexus' => 'Veto Nexus',
                                                        'Corpo Sucus' => 'Corpo Sucus',
                                                        'Vocare Inmortui' => 'Vocare Inmortui',
                                                        'Quaestio Chaos' => 'Quaestio Chaos',
                                                        'Porta Exterreo' => 'Porta Exterreo',
                                                        'Inanis' => 'Inanis',
                                                        'Effio Chaos' => 'Effio Chaos',
                                                        'Vocare Interdict' => 'Vocare Interdict',
                                                        'Reicio' => 'Reicio',
                                                        'Veto Memoria' => 'Veto Memoria',
                                                        'Vocare Phantasma' => 'Vocare Phantasma',
                                                        'Ligo Irae' => 'Ligo Irae',
                                                        'Trepidatio' => 'Trepidatio',
                                                        'Terrere' => 'Terrere',
                                                        'Porta Fracti' => 'Porta Fracti',
                                                        'Mille Acus' => 'Mille Acus',
                                                        'Cruciatus' => 'Cruciatus',
                                                        'Vocare Tormentis' => 'Vocare Tormentis',
                                                        'Tedium' => 'Tedium',
                                                        'Vinculum' => 'Vinculum',
                                                        'Malum Specio' => 'Malum Specio',
                                                        'Simulacrum' => 'Simulacrum',
                                                        'Vocatus Malum' => 'Vocatus Malum',
                                                        'Magniforma' => 'Magniforma',
                                                        'Pandemalum' => 'Pandemalum',
                                                        'Pestis' => 'Pestis',
                                                        'Morbus' => 'Morbus',
                                                        'Rubigo' => 'Rubigo',
                                                        'Corpus Verto' => 'Corpus Verto',
                                                        'Vocare Toxicum' => 'Vocare Toxicum',
                                                        'Venatio' => 'Venatio',
                                                        'Vocare Furia' => 'Vocare Furia',
                                                        'Dissolutium' => 'Dissolutium',
                                                        'Concavum' => 'Concavum',
                                                        'Atrox' => 'Atrox',
                                                        'Legere' => 'Legere',
                                                        'Plaga' => 'Plaga',
                                                        'Vinco' => 'Vinco',
                                                        'Vis' => 'Vis',
                                                        'Impero' => 'Impero',
                                                        'Dissaeptum' => 'Dissaeptum',
                                                    ]),
                                                Select::make('skill_ch')
                                                    ->hint(function (Get $get, Set $set) {
                                                        $result = self::limitskills($get, $set);
                                                        return count($get('skill_ch')) . ' von insgesamt ' . $result['limit'];
                                                    })
                                                    ->multiple()
                                                    ->live()
                                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                                        self::limitskills($get, $set);
                                                    })
                                                    ->disabled(function ($state, Get $get) {
                                                        return self::isSkillactive($get, 'CH');
                                                    })
                                                    ->options([
                                                        'Quaestio Spiri' => 'Quaestio Spiri',
                                                        'Conventus' => 'Conventus',
                                                        'Sensus' => 'Sensus',
                                                        'Alienus' => 'Alienus',
                                                        'Aenigma' => 'Aenigma',
                                                        'Vocare Spiri' => 'Vocare Spiri',
                                                        'Peregrinus' => 'Peregrinus',
                                                        'Pax' => 'Pax',
                                                        'Nuntius' => 'Nuntius',
                                                        'Veto Spiri' => 'Veto Spiri',
                                                        'Lacero Spiri' => 'Lacero Spiri',
                                                        'Machina Vitam' => 'Machina Vitam',
                                                        'Artifex' => 'Artifex',
                                                        'Inspiratio' => 'Inspiratio',
                                                        'Ars' => 'Ars',
                                                        'Clavicarius' => 'Clavicarius',
                                                        'Ico' => 'Ico',
                                                        'Ira' => 'Ira',
                                                        'Spiritelum' => 'Spiritelum',
                                                        'Sententia' => 'Sententia',
                                                        'Ferus' => 'Ferus',
                                                        'Recuso' => 'Recuso',
                                                        'Recordatio' => 'Recordatio',
                                                        'Pertinax' => 'Pertinax',
                                                        'Detineo' => 'Detineo',
                                                        'Effio Spiri' => 'Effio Spiri',
                                                        'Ligo Anima' => 'Ligo Anima',
                                                        'Spiri Vitae' => 'Spiri Vitae',
                                                        'Nanciscor' => 'Nanciscor',
                                                        'Sermo' => 'Sermo',
                                                        'Meretrix' => 'Meretrix',
                                                        'Cupiditas' => 'Cupiditas',
                                                        'Affectio' => 'Affectio',
                                                        'Ines' => 'Ines',
                                                        'Fortuna' => 'Fortuna',
                                                        'Cavillor' => 'Cavillor',
                                                        'Vocare Credo' => 'Vocare Credo',
                                                        'Velox' => 'Velox',
                                                        'Exeo' => 'Exeo',
                                                    ]),
                                            ])
                                    ])
                            ]),
                        Tabs\Tab::make('Ausrüstung')
                            ->schema([
                                Section::make('natürliche Waffe')
                                    ->collapsible()
                                    ->description('Natürliche Waffe')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Radio::make('nw_gattung')
                                                    ->label('Waffengattung')
                                                    ->required()
                                                    ->options([
                                                        'Nahkampfwaffe' => 'Nahkampfwaffe',
                                                        'Fernkampfwaffe' => 'Fernkampfwaffe (nur Spucker)',
                                                    ]),
                                                TextInput::make('nw_quality')
                                                    ->label('QS')
                                                    ->required(),
                                                Select::make('nw_damage_type')
                                                    ->label('Schadensarten')
                                                    ->required()
                                                    ->multiple()
                                                    ->options([
                                                        'stumpf' => 'ST (Stumpf)',
                                                        'schnitt' => 'AG (Schnitt)',
                                                        'stich' => 'GE (Stich)',
                                                        'arkan' => 'WE (Arkan)',
                                                        'Elementar' => 'IN (Elementar)',
                                                        'Chaos' => 'MU (Chaos)',
                                                        'Spirituell' => 'CH (Spirituell)',
                                                    ]),
                                            ]),
                                        Grid::make(3)
                                            ->schema([
                                                Textinput::make('nw_aw')
                                                    ->label('AW')
                                                    ->required()
                                                    ->numeric(),
                                                Textinput::make('nw_vw')
                                                    ->label('VW')
                                                    ->required()
                                                    ->numeric(),
                                                Textinput::make('nw_tw')
                                                    ->label('TW')
                                                    ->required()
                                                    ->numeric(),
                                            ]),
                                    ]),
                                Section::make('Ausrüstung')
                                    ->collapsible()
                                    ->description('Wähle die aktuelle Ausrüstung')
                                    ->schema([
//                                        Repeater::make('equipmentAssignments')
//                                            ->relationship('equipmentAssignments') // wichtig! Dadurch wird character_id automatisch gesetzt
//                                            ->label('Ausrüstung zuweisen')
//                                            ->schema([
//                                                Select::make('equipmentAssignments')
//                                                    ->label('Ausrüstung wählen')
//                                                    ->live()
////                                                    ->options(Equipment::all()->pluck('name', 'id'))
//                                                    ->searchable(['name', 'id'])
//                                                    ->preload(),
//                                                Toggle::make('equipped')
//                                                    ->label('Ausgerüstet')
//                                                    ->inline(false),
//                                            ])
//                                            ->addActionLabel('weitere Ausrüstung hinzufügen')
                                    ]),
                            ]),
                    ]),
            ]);
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
        $set('main_stat_value',  self::getResources($get('ko_toggle'), $get('leiteigenschaft1'), $get('leiteigenschaft2'), self::getAttributeArray($get), $get('xp')));
    }

    public static function calculateLeps(Get $get, Set $set): void
    {

        $ko = $get('ko');
        $xp = $get('xp');
        $le1 = $get('leiteigenschaft1');
        $le2 = $get('leiteigenschaft2');
        $ko_toggle = $get('ko_toggle');

        if ($ko_toggle) {
            if ($le1 === 'KO' && $le2 === 'KO') {
                $set('leps', $ko * 5 + $xp);
            } elseif ($le1 === 'KO' || $le2 === 'KO') {
                $set('leps', $ko * 3 + $xp);
            } else {
                $set('leps', $ko * 2 + $xp);
            }
        } else {
            $set('leps', $ko * 2 + $xp);
        }

    }

    public static function limitclassability1(Get $get, Set $set) : int
    {
        $xp = $get('xp');
        $limit = match (true) {
//            $xp >= 21 => 6,
//            $xp >= 16 => 5,
//            $xp >= 11 => 4,
            $xp >= 4 => 3,
            $xp >= 2 => 2,
            default => 1,
        };

        if (is_array($get('classability1')) && count($get('classability1')) > $limit) {
            $set('classability1', array_slice($get('classability1'), 0, $limit));

            // Warnung anzeigen
            Notification::make()
                ->title("Du darfst auf Stufe {$xp} maximal {$limit} Klassenfertigkeiten wählen.")
                ->danger()
                ->send();
        }
        return $limit;
    }
    public static function limitclassability2(Get $get, Set $set) : int
    {
        $xp = $get('xp');
        $limit = match (true) {
//            $xp >= 21 => 6,
//            $xp >= 16 => 5,
//            $xp >= 11 => 4,
            $xp >= 11 => 2,
            $xp >= 7 => 1,
            default => 0,
        };

        if (is_array($get('classability1')) && count($get('classability1')) > $limit) {
            $set('classability1', array_slice($get('classability1'), 0, $limit));

            // Warnung anzeigen
            Notification::make()
                ->title("Du darfst auf Stufe {$xp} maximal {$limit} Klassenfertigkeiten wählen.")
                ->danger()
                ->send();
        }
        return $limit;
    }
    public static function limitclassability3(Get $get, Set $set) : int
    {
        $xp = $get('xp');
        $limit = match (true) {
//            $xp >= 21 => 6,
//            $xp >= 16 => 5,
//            $xp >= 11 => 4,
            $xp >= 22 => 2,
            $xp >= 16 => 1,
            default => 0,
        };

        if (is_array($get('classability1')) && count($get('classability1')) > $limit) {
            $set('classability1', array_slice($get('classability1'), 0, $limit));

            // Warnung anzeigen
            Notification::make()
                ->title("Du darfst auf Stufe {$xp} maximal {$limit} Klassenfertigkeiten wählen.")
                ->danger()
                ->send();
        }
        return $limit;
    }


    public static function maxEigenschaften($get, $set)
    {
        // Liste aller Eigenschaftsfelder
        $fields = ['ko', 'st', 'ag', 'ge', 'we', 'in', 'mu', 'ch'];

        // Erlaubte Maximal-Summe berechnen
        $xp = (int) $get('xp');
        $max = 95 + $xp;

        // Aktuelle Summe der Eigenschaften berechnen
        $sum = 0;
        foreach ($fields as $field) {
            $value = (int) $get($field);
            $sum += $value;
        }
        $set('maxeig', $max);
        $set('sumeig', $sum);
        $limit = min($xp + 13, 22);

        // Falls Summe zu hoch ist → Warnung
        if ($sum > $max) {
            Notification::make()
                ->title("Die Summe deiner Eigenschaften darf bei XP {$xp} maximal {$max} betragen. Aktuell: {$sum}.")
                ->danger()
                ->send();
        }

        return [
            'maxeig' => $max,
            'sumeig' => $sum,
            'limit' => $limit,
        ];
    }

    public static function limitcraftability($get, $set)
    {
        $xp = $get('xp');
        $limit = match (true) {
            $xp >= 4 => 3,
            $xp >= 2 => 2,
            default => 1,
        };


        if (is_array($get('handwerkskenntnisse')) && count($get('handwerkskenntnisse')) > $limit) {
            $set('handwerkskenntnisse', array_slice($get('handwerkskenntnisse'), 0, $limit));

            // Warnung anzeigen
            Notification::make()
                ->title("Du darfst auf Stufe {$xp} maximal {$limit} Handwerkskenntnisse wählen.")
                ->danger()
                ->send();
        }
        return $limit;
    }
    public static function limitskills ($get)
    {
        $skillFields = [
            'skill_ko', 'skill_st', 'skill_ag', 'skill_ge',
            'skill_we', 'skill_in', 'skill_mu', 'skill_ch',
        ];
        $xp = $get('xp');
        $limit = match (true) {
            $xp >= 21=> 15,
            $xp >= 20=> 14,
            $xp >= 17=> 13,
            $xp >= 15=> 12,
            $xp >= 13=> 11,
            $xp >= 10=> 10,
            $xp >= 9=> 9,
            $xp >= 8=> 8,
            $xp >= 7=> 7,
            $xp >= 5=> 6,
            $xp >= 3=> 5,
            $xp >= 2=> 4,
            default   => 2,
        };

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

        // Gesamtliste aller ausgewählten Skills
        $flatList = array_merge(...array_values($allSkills));


        // Wenn das Limit überschritten wurde Warnung anzeigen
        if (count($flatList) > $limit) {
            Notification::make()
                ->title("Du darfst auf Stufe {$xp} maximal {$limit} Waffen- oder Aspektfertigkeiten wählen.")
                ->danger()
                ->send();
        }
        return [
            'flatList' => $flatList,
            'limit' => $limit,
        ];
    }
    public static function isSkillactive($get, string $skillkey): bool
    {
        if ($get('leiteigenschaft1') === $skillkey || $get('leiteigenschaft2') === $skillkey) {
            return false;
        }
        return true;
    }


}
