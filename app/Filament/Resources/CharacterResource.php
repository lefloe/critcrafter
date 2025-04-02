<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CharacterResource\Pages;
use App\Filament\Resources\CharacterResource\RelationManagers;
use App\Models\Character;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                            TextInput::make('name')
                                            ->label('Name')
                                            ->required()
                                            ->maxLength(255),
                            Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->maxLength(800),
                            Select::make('system_id')
                                ->relationship('system', 'name')
                                ->createOptionAction(fn($action) => $action->slideOver())
                                ->createOptionForm([
                                    TextInput::make('name'),
                                ])
                                ->editOptionForm([
                                    TextInput::make('name'),
                                ])
                                ->required(),
                        ]),
                        Tabs\Tab::make('Tab 2')
                        ->schema([
                            Section::make('Archetyp, Leiteigenschaften')
                            ->description('Leiteigenschaften und Archetyp auswählen')
                            ->collapsible()
                            ->schema([
                                Grid::make(2) // Grid mit 2 Spalten
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

                                        // set the main_stat_value
                                        $mainCharacteristic1 = $get('leiteigenschaft1');

                                        if ($mainCharacteristic1) {
                                            $mainStatValue = $get($mainCharacteristic1);
                                            $set('main_stat_value', $mainStatValue);
                                        }
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
                                    ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                                    $set('archetype', self::getArchetype($state, $get('leiteigenschaft1')))
                                    ),
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
                                Select::make('race')
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
                                                $set('main_stat_value', $get('ko') + );
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
                                // Select::make('race')
                                //     ->options([
                                //         'Ainu' => 'Ainu',
                                //         'Alkonost' => 'Alkonost',
                                //         'Balachko' => 'Balachko',
                                //         'Bastet' => 'Bastet',
                                //         'Crocotta' => 'Crocotta',
                                //         'Karura' => 'Karura',
                                //         'Leshy' => 'Leshy',
                                //         'Vanaras' => 'Vanaras',
                                //         'Vodyanoy' => 'Vodyanoy',
                                //         'Vukodlak' => 'Vukodlak',
                                //         'Chepri' => 'Chepri',
                                //     ]),
                                TextInput::make('experience-level')
                                ->numeric()
                                ->step(1)
                                ->maxValue(22)
                                ->minValue(1)
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
