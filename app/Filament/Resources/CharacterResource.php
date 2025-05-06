<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Resources\CharacterResource\Pages;
use App\Filament\Resources\CharacterResource\RelationManagers;
use Filament\Notifications\Notification;
use App\Models\Character;
use App\Models\Equipment;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Radio;
use MongoDB\BSON\Javascript;


class CharacterResource extends Resource
{
    protected static ?string $model = Character::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->statePath('')
                    ->tabs([
                        Tabs\Tab::make('Tab 1')
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
                            Grid::make(2)
                            ->schema([
                                Select::make('system_id')
                                ->relationship('system', 'name')
//                                ->createOptionAction(fn($action) => $action->slideOver())
//                                ->createOptionForm([
//                                    TextInput::make('name'),
//                                ])
//                                ->editOptionForm([
//                                    TextInput::make('name'),
//                                ])
                                ->preload()
                                ->required(),
                                FileUpload::make('portrait')
                                ->label('Portrait')
                                ->image()
                                ->directory('portraits')        // Speicherort im Storage
                                ->preserveFilenames()
                                ->imagePreviewHeight('150')     // Vorschaugröße
                                ->openable()
                                ->downloadable()
                                ->maxSize(2048)                 // in KB (2 MB)
                                ->acceptedFileTypes(['image/jpeg', 'image/png'])

                            ]),
                        ]),
                        Tabs\Tab::make('Tab 2')
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
                            Section::make('Eigenschaften')
                                ->description(function (Get $get, Set $set) {
                                    $value = count($get('klassenfertigkeiten')) ?? 10;
                                    $result = self::maxEigenschaften($get, $set);

                                    return "{$result['sumeig']}   von {$result['maxeig']} Punkten vergeben. Maximal {$result['limit']} pro Eigenschaft.";
                                })
                                ->columns([
                                    'lg' => 2,
                                    'sm' => 1,
                                ])
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
                        Tabs\Tab::make('Tab 3')
                        ->schema([
                                Section::make('Klassenfertigkeiten, Handwerkskenntnis, Überlieferungen')
                                ->description('Klassenfertigkeiten, Handwerkskenntnis und Überlieferungen auswählen')
                                ->collapsible()
                                ->schema([
                                    Grid::make(3)
                                    ->schema([
                                        Select::make('klassenfertigkeiten')
                                        ->multiple()
                                        ->live()
                                        ->options([
                                            'Animist I' => 'Animist I',
                                            'Barde I' => 'Barde I',
                                            'Berserker I' => 'Berserker I',
                                            'Druide I' => 'Druide I',
                                            'Elementarist I' => 'Elementarist I',
                                            'Fechter I' => 'Fechter I',
                                            'Gladiator I' => 'Gladiator I',
                                            'Hexer I' => 'Hexer I',
                                            'Klingenmeister I' => 'Klingenmeister I',
                                            'Koloss I' => 'Koloss I',
                                            'Krieger I' => 'Krieger I',
                                            'Monsterjäger I' => 'Monsterjäger I',
                                            'Mystiker I' => 'Mystiker I',
                                            'Nekromant I' => 'Nekromant I',
                                            'Paladin I' => 'Paladin I',
                                            'Pirscher I' => 'Pirscher I',
                                            'Runenschnitzer I' => 'Runenschnitzer I',
                                            'Sappeur I' => 'Sappeur I',
                                            'Schamane I' => 'Schamane I',
                                            'Schreckensritter I' => 'Schreckensritter I',
                                            'Seher I' => 'Seher I',
                                            'Späher I' => 'Späher I',
                                            'Spiritualist I' => 'Spiritualist I',
                                            'Tänzer I' => 'Tänzer I',
                                            'Templer I' => 'Templer I',
                                            'Thaumaturg I' => 'Thaumaturg I',
                                            'Waldläufer I' => 'Waldläufer I',
                                            'Eigenschaftsbonus' => 'Eigenschaftsbonus',
                                            'Basistalentbonus' => 'Basistalentbonus',
                                        ])
                                        ->afterstateUpdated(function (Get $get, Set $set) {
                                            self::limitKlassenfertigkeiten($get, $set);
                                        }

                                        )
                                        ->hint(function (Get $get, Set $set) {
                                            $value = count($get('klassenfertigkeiten')) ?? 10;
                                            $limit = self::limitKlassenfertigkeiten($get, $set);
                                            return "{$value} von {$limit}";
                                        }),
                                        Select::make('handwerkskenntnisse')
                                        ->multiple()
                                        ->live()
                                        ->default(['Handelswaren','Werkzeuge'])
                                        ->afterstateUpdated(function (Get $get, Set $set) {
                                            self:self::limithandwerk($get, $set);
                                        })
                                        ->options([
                                            'Handelswaren' => 'Handelswaren',
                                            'Werkzeuge' => 'Werkzeuge',
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
                                            $value = count($get('handwerkskenntnisse')) ?? 10;
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
                        Tabs\Tab::make('Tab 4')
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
                                        Select::make('nw_quality')
                                        ->label('QS')
                                        ->required()
                                        ->options(EquipmentResource::getQS()),
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
                                ->description('Wähle die aktuelle Ausrütung')
                                ->schema([
                                    Repeater::make('equipmentAssignments')
                                    ->relationship('equipmentAssignments') // wichtig! Dadurch wird character_id automatisch gesetzt
                                    ->label('Ausrüstung zuweisen')
                                    ->schema([
                                        Select::make('equipmentAssignments')
                                        ->label('Ausrüstung wählen')
                                        ->live()
                                        ->options(Equipment::all()->pluck('name', 'id'))
                                        ->searchable(['name', 'id'])
                                        ->preload(),
                                        Toggle::make('equipped')
                                            ->label('Ausgerüstet')
                                            ->inline(false),
                                    ])
                                    ->addActionLabel('weitere Ausrüstung hinzufügen')
                                ]),
                            ]),
//                        Tabs\Tab::make('Zusammenfassung')
                    ]),
            ]);


    }

    protected function handleRecordCreation(array $data): Model
{
    return static::getModel()::create($data);
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



    public static function getAttributeArray(Get $get): array
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

    public static function limitKlassenfertigkeiten(Get $get, Set $set)
    {
        $xp = $get('xp');
        $limit = match (true) {
            $xp >= 21 => 6,
            $xp >= 16 => 5,
            $xp >= 11 => 4,
            $xp >= 7 => 3,
            $xp >= 4 => 2,
            default => 1,
        };

        if (is_array($get('klassenfertigkeiten')) && count($get('klassenfertigkeiten')) > $limit) {
            $set('klassenfertigkeiten', array_slice($get('klassenfertigkeiten'), 0, $limit));

            // Warnung anzeigen
            Notification::make()
                ->title("Du darfst auf Stufe {$xp} maximal {$limit} Klassenfertigkeiten wählen.")
                ->danger()
                ->send();
        }
        return $limit;
    }

    public static function isSkillactive(Get $get, string $skillkey): bool
    {
        if ($get('leiteigenschaft1') === $skillkey || $get('leiteigenschaft2') === $skillkey) {
            return false;
        }
        return true;
    }

    public static function limitskills (Get $get, Set $set)
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

    public static function limithandwerk(Get $get, Set $set)
    {
        $xp = $get('xp');
        $limit = match (true) {
            $xp >= 11 => 5,
            $xp >= 9 => 4,
            $xp >= 5 => 3,
            $xp >= 3 => 2,
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

    public static function maxEigenschaften(Get $get, Set $set)
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
                ->persistent()
                ->send();
        }

        return [
            'maxeig' => $max,
            'sumeig' => $sum,
            'limit' => $limit,
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                          ->label('Name')
                          ->sortable()
                          ->weight('bold')
                          ->searchable(),
                TextColumn::make('system.name')
                          ->sortable()
                          ->label('System'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('pdf')
                ->label('PDF')
                ->icon('heroicon-o-printer')
                ->url(fn (Character $record) => route('character.print', ['id' => $record->id]))
                ->openUrlInNewTab(),
//                Tables\Actions\Action::make('fillable_pdf')
//                ->label('fillable PDF')
//                ->icon('heroicon-o-printer')
//                ->url(fn (Character $record) => route('fill-character.print', ['id' => $record->id]))
//                ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCharacters::route('/'),
            'create' => Pages\CreateCharacter::route('/create'),
            'edit' => Pages\EditCharacter::route('/{record}/edit'),
        ];
    }
}
