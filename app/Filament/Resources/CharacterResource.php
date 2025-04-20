<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CharacterResource\Pages;
use App\Filament\Resources\CharacterResource\RelationManagers;
use App\Models\Character;
use App\Models\Equipment;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Get;
use Filament\Forms\Set;





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
                                TextInput::make('experience-level')
                                ->Label('Erfahrungsstufe')
                                ->numeric()
                                ->step(1)
                                ->maxValue(22)
                                ->required()
                                ->minValue(1),
                            ]),
                            Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->maxLength(800),
                            Grid::make(2)
                            ->schema([
                                Select::make('system_id')
                                ->relationship('system', 'name')
                                ->createOptionAction(fn($action) => $action->slideOver())
                                ->createOptionForm([
                                    TextInput::make('name'),
                                ])
                                ->editOptionForm([
                                    TextInput::make('name'),
                                ])
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
                                        // set the achetype 
                                        $set('archetype', self::getArchetype($state, $get('leiteigenschaft2'))); 
                                        $data = self::getAttributeArray($get);                             
                                        $set('main_stat_value', self::getResources($state, $data, $get('leiteigenschaft2')));
                                    })
                                    ->afterStateHydrated(function ($state, Get $get, Set $set) {
                                        $set('archetype', self::getArchetype($state, $get('leiteigenschaft2')));   //sets archetype
                                        $data = self::getAttributeArray($get);
                                        $set('main_stat_value', self::getResources($get('leiteigenschaft2'), $data, $state));
    
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
                                        $data = self::getAttributeArray($get);                             
                                        $set('main_stat_value', self::getResources($get('leiteigenschaft1'), $data, $state));
                                    })
                                    ->afterStateHydrated(function ($state, Get $get, Set $set) {
                                        $set('archetype', self::getArchetype($state, $get('leiteigenschaft1')));   //sets archetype
                                        $data = self::getAttributeArray($get);
                                        $set('main_stat_value', self::getResources($get('leiteigenschaft1'), $data, $state));

                                    }),
                                ]),
                            Grid::make(2)
                            ->schema([
                                TextInput::make('archetype')
                                ->label('Archetyp')
                                ->live()
                                ->default(fn (callable $get) => self::getArchetype($get('leiteigenschaft1'), $get('leiteigenschaft2')))
                                ->disabled(),

                                TextInput::make('main_stat_value')
                                ->label('Ressourcen')
                                ->live()
                                ->disabled(),
                            ])
                            ]),
                            Section::make('Rasse und Rassenmerkmale')
                            ->description('Rasse und Rassenmerkmale auswählen')
                            ->collapsible()
                            ->schema([
                                Grid::make(2)
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
                                ]),
                                Select::make('rassenmerkmale')
                                ->multiple()
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
                                ])                            
                            ]),
                            Section::make('Eigenschaften')
                            ->collapsible()
                            ->schema([
                                Grid::make(4)
                                ->schema([
                                    TextInput::make('ko') // Konstitution
                                        ->label('Konstitution (KO)')
                                        ->numeric()
                                        ->minValue(0)
                                        ->live()
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            // update LeP value
                                            $set('leps', $get('ko') * 2);

                                            // If ko is the main characteristic, update main_stat_value
                                            if ($get('leiteigenschaft1') == 'KO') {
                                                $set('main_stat_value', $get('ko'));
                                            }
                                        })
                                        ->required(),
                                    TextInput::make('st') // Stärke
                                        ->label('Stärke (ST)')
                                        ->numeric()
                                        ->minValue(0)
                                        ->live()
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            // update Tragkraft value
                                            $set('tragkraft', $get('st'));
                                            
                                            // If st is the main characteristic, update main_stat_value
                                            if ($get('leiteigenschaft1') == 'ST') {
                                                $set('main_stat_value', $get('st'));
                                            }
                                        })
                                        ->required(),
                                    TextInput::make('ag') // Agilität
                                        ->label('Agilität (AG)')
                                        ->numeric()
                                        ->minValue(0)
                                        ->live()
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            // update Geschwindigkeit value
                                            $set('geschwindigkeit', round($get('ag') / 2));
                                            
                                            // If ag is the main characteristic, update main_stat_value
                                            if ($get('leiteigenschaft1') == 'AG') {
                                                $set('main_stat_value', $get('ag'));
                                            }
                                        })
                                        ->required(),
                                    TextInput::make('ge') // Geschick
                                        ->label('Geschick (GE)')
                                        ->numeric()
                                        ->minValue(0)
                                        ->live()
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            // update Handwerksbonus value
                                            $set('handwerksbonus', $get('ge') - 12);
                                            
                                            // If ge is the main characteristic, update main_stat_value
                                            if ($get('leiteigenschaft1') == 'GE') {
                                                $set('main_stat_value', $get('ge'));
                                            }
                                        })
                                        ->required(),
                                    TextInput::make('we') // Weisheit
                                        ->label('Weisheit (WE)')
                                        ->numeric()
                                        ->minValue(0)
                                        ->live()
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            // update Kontrollwiderstand value
                                            $set('kontrollwiderstand', $get('we') - 12);
                                            
                                            // If we is the main characteristic, update main_stat_value
                                            if ($get('leiteigenschaft1') == 'WE') {
                                                $set('main_stat_value', $get('we'));
                                            }
                                        })
                                        ->required(),
                                    TextInput::make('in') // Instinkt
                                        ->label('Instinkt (IN)')
                                        ->numeric()
                                        ->minValue(0)
                                        ->live()
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            // update Initiative value
                                            $set('initiative', round($get('in') / 2));
                                            
                                            // If in is the main characteristic, update main_stat_value
                                            if ($get('leiteigenschaft1') == 'IN') {
                                                $set('main_stat_value', $get('in'));
                                            }
                                        })
                                        ->required(),
                                    TextInput::make('mu') // Mut
                                        ->label('Mut (MU)')
                                        ->numeric()
                                        ->minValue(0)
                                        ->live()
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            // update Verteidigung value
                                            $set('verteidigung', $get('mu') - 12);
                                            
                                            // If mu is the main characteristic, update main_stat_value
                                            if ($get('leiteigenschaft1') == 'MU') {
                                                $set('main_stat_value', $get('mu'));
                                            }
                                        })
                                        ->required(),
                                    TextInput::make('ch') // Charisma
                                        ->label('Charisma (CH)')
                                        ->numeric()
                                        ->minValue(0)
                                        ->live()
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            // update Seelenpunkte value
                                            $set('seelenpunkte', $get('ch') * 2);
                                            
                                            // If ch is the main characteristic, update main_stat_value
                                            if ($get('leiteigenschaft1') == 'CH') {
                                                $set('main_stat_value', $get('ch'));
                                            }
                                        })
                                        ->required(),
                                ])

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
                                Section::make('Klassenfertigkeiten und Handwerkskenntnis wählen')
                                ->description('Klassenfertigkeiten und Handwerkskenntnis auswählen')
                                ->collapsible()
                                ->schema([
                                    Grid::make(2)
                                    ->schema([
                                        Select::make('klassenfertigkeiten')
                                        ->multiple()
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
                                        ]),
                                        Select::make('handwerkskenntnisse')
                                        ->multiple()
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
                                        ]),
                                    ]),                                
                                ]),
                                Section::make('Fertgkeiten wählen')
                                ->description('Aspekt- und Waffenfertigkeiten auswählen')
                                ->collapsible()
                                ->schema([
                                    Grid::make(4)
                                    ->schema([
                                        Select::make('skill_ko')
                                        ->multiple()
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
                                        ]),
                                        Select::make('skill_st')
                                        ->multiple()
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
                                        ->multiple()
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
                                        ->multiple()
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
                                        ->multiple()
                                        ->options([
                                            'Illuminos' => 'Illuminos',
                                            'Spiri Exvocare' => 'Spiri Exvocare',
                                            'Soliri' => 'Soliri',
                                            'Lux Columna' => 'Lux Columna',
                                            'Purgato' => 'Purgato',
                                            'Oculux' => 'Oculux',
                                            'Calefaciendo' => 'Calefaciendo',
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
                                        ->multiple()
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
                                        ->multiple()
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
                                        ->multiple()
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
                                            'Recuso' => 'Recuso',
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
                                Select::make('lore')
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
                                Section::make('equip_items')
                                ->description('Wähle die aktuelle Ausrütung')
                                ->schema([
                                    Repeater::make('equipmentAssignments')
                                    ->relationship('equipmentAssignments') // wichtig! Dadurch wird character_id automatisch gesetzt
                                    ->label('Ausrüstung zuweisen')
                                    ->schema([
                                        Select::make('equipment_id')
                                        ->label('Ausrüstung wählen')
                                        ->options(\App\Models\Equipment::all()->pluck('name', 'id'))
                                        ->required(),
                                        Toggle::make('equipped')
                                            ->label('Ausgerüstet')
                                            ->inline(false),
                                    ])
                                    ->createItemButtonLabel('weitere Ausrüstung hinzufügen')
                                    // ->columns(3)
                                ]),
                            ]),
                        Tabs\Tab::make('Zusammenfassung')
                            ->schema([

                            ])
                    ]),
            ]);


    }

    protected function handleRecordCreation(array $data): Model
{
    dd($data);
    return static::getModel()::create($data);
}

    protected static function getArchetype(?string $leiteigenschaft1, ?string $leiteigenschaft2): string
    {
        if (!$leiteigenschaft1 || !$leiteigenschaft2) {
            return 'Unbekannt'; // Standardwert, wenn ein Wert fehlt
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

        foreach ($archetypeMap as $attributes => $archetype) {
            if (in_array($leiteigenschaft1, $attributes) && in_array($leiteigenschaft2, $attributes)) {
                return $archetype;
            }
        }

        return 'Unbekannt'; // Falls keine Kombination passt

    }

    public static function getAttributeArray(Get $get): array
    {
        return [
            'KO' => $get('ko'),
            'ST' => $get('st'),
            'AG' => $get('ag'),
            'GE' => $get('ge'),
            'WE' => $get('we'),
            'IN' => $get('in'),
            'MU' => $get('mu'),
            'CH' => $get('ch'),
        ];

    }

    public static function getResources(?string $leiteigenschaft2, ?array $data, ?string $leiteigenschaft1): int
    {
        $value1 = isset($data[$leiteigenschaft1]) ? (int)$data[$leiteigenschaft1]: 0;
        $value2 = isset($data[$leiteigenschaft2]) ? (int)$data[$leiteigenschaft2]: 0;

        return $value1 + $value2;
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
