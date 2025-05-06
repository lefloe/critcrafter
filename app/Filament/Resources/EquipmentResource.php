<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipmentResource\Pages;
use App\Filament\Resources\EquipmentResource\RelationManagers;
use App\Models\Equipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Fieldset;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EquipmentResource extends Resource
{
    protected static ?string $model = Equipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('allgemein')
            ->description('Art der Ausrütung wählen')
            ->schema([
                Grid::make(2)
                ->schema([
                    TextInput::make('name')
                    ->required(),
                    Textarea::make('description')
                    ->label('Beschreibe die Ausrüstung')
                    ->maxLength(800),
                    Select::make('quality')
                    ->required()
                    ->options(self::getQS()),
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
                        'Anwendung' => 'Anwendung',
                        'sonstiges' => 'sonstiges',
                    ])
                    ->reactive()
                ])
            ]),
            Section::make('weapon')
            ->description('Werte der Waffe wählen')
            ->schema([
                Textinput::make('hwp')
                ->label('Handwerkspunkte')
                ->numeric()
                ->step(1)
                ->minValue(1)
                ->maxValue(99),
                Radio::make('waffengattung')
                ->options([
                    'Nahkampfwaffe' => 'Nahkampfwaffe',
                    'Fernkampfwaffe' => 'Fernkampfwaffe',
                ]),
                Textinput::make('angriffswert')
                ->label('Angriffswert')
                ->numeric()
                ->step(1),
                Select::make('damage_type')
                ->Label('Leiteigenschaften (Schadensarten)')
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
                Textinput::make('trefferwuerfel')
                ->label('Trefferwürfel')
                ->numeric()
                ->step(1)
                ->minvalue(1)
                ->maxvalue(9),
                Textinput::make('traglast')
                ->label('Traglast')
                ->numeric()
                ->step(1)
                ->minvalue(1)
                ->maxvalue(9),
                Select::make('wp_erweiterungen')
                ->label('Erweiterungen')
                ->multiple()
                ->options([
                    'der Modularität' => 'der Modularität (0 HwP)',
                    'der Finalität' => 'der Finalität (0 HwP)',
                    'der Zweihändigkeit' => 'der Zweihändigkeit (0 HwP)',
                    'der Einhändigkeit' => 'der Einhändigkeit (0 HwP)',
                    'der Nebenhand' => 'der Nebenhand (0 HwP)',
                    'der Härtung' => 'der Härtung (3 HwP)',
                    'der Einzigartigkeit' => 'der Einzigartigkeit (3 HwP)',
                    'der Überheblichkeit' => 'der Überheblichkeit (3 HwP)',
                    'des Attentäters' => 'des Attentäters (3 HwP)',
                    'an der Kette' => 'an der Kette (3 HwP)',
                    'der einfachen Handhabung' => 'der einfachen Handhabung (3 HwP)',
                    'des Duells' => 'des Duells (5 HwP)',
                    'des Gemetzels' => 'des Gemetzels (5 HwP)',
                    'der Grausamkeit' => 'der Grausamkeit (5 HwP)',
                    'des Tüftlers' => 'des Tüftlers (5 HwP)',
                    'der Schlagkraft' => 'der Schlagkraft (7 HwP)',
                    'der Präzision' => 'der Präzision (7 HwP)',
                    'der Flexibilität' => 'der Flexibilität (7 HwP)',
                    'der Effizienz' => 'der Effizienz (9 HwP)',
                    'der Kampfkunst' => 'der Kampfkunst (9 HwP)',
                    'der Brutalität' => 'der Brutalität (9 HwP)',
                    'des Hinterhalts' => 'des Hinterhalts (13 HwP)',
                    'der Vorhut' => 'der Vorhut  (13 HwP)',
                    'des Jenseits' => 'des Jenseits  (13 HwP)',
                    'der Revolution' => 'der Revolution  (13 HwP)',
                    'mit Köcher' => 'mit Köcher (3 HwP)',
                ])
                ->reactive(),
            ])
            ->visible(fn (callable $get) => in_array($get('item_type'), ['Waffe'])),
            Section::make('Rüstung')
            ->description('Werte der Rüstung wählen')
            ->schema([
                Textinput::make('hwp')
                ->label('Handwerkspunkte')
                ->numeric()
                ->step(1)
                ->minValue(1)
                ->maxValue(99),
                Textinput::make('passive_verteidigung')
                ->label('passive Verteidigung')
                ->numeric()
                ->step(1),
                Grid::make(4)
                ->schema([
                    Textinput::make('rs_schnitt')
                    ->label('Rüstungsschutz Schnitt')
                    ->numeric(),
                    Textinput::make('rs_stumpf')
                    ->label('Rüstungsschutz Stumpf')
                    ->numeric(),
                    Textinput::make('rs_stich')
                    ->label('Rüstungsschutz Stich')
                    ->numeric(),
                    Textinput::make('rs_elementar')
                    ->label('Rüstungsschutz Elementar')
                    ->numeric(),
                    Textinput::make('traglast')
                    ->label('Traglast')
                    ->numeric()
                    ->step(1)
                    ->minvalue(1)
                    ->maxvalue(9),
                ]),
                Grid::make(2)
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
                    Select::make('rs_erweiterungen')
                    ->label('Erweiterungen')
                    ->multiple()
                    ->options([
                        'getarnt' => 'Getarnt (3 HwP)',
                        'gleitend' => 'Gleitend (3 HwP)',
                        'mit kletterausrüstung' => 'mit Kletterausrüstung (3 HwP)',
                        'gehärtet' => 'Gehärtet (3 HwP)',
                        'flexibel' => 'Flexibel (5 HwP)',
                        'verstärkt' => 'Verstärkt (5 HwP)',
                        'mechanisch' => 'Mechanisch (5 HwP)',
                        'passgenau' => 'Passgenau (5 HwP)',
                        'gelenkig' => 'Gelenkig (5 HwP)',
                        'des artisten' => 'des Artisten (7 HwP)',
                        'des elements' => 'des Elements (7 HwP)',
                        'geläutert' => 'Geläutert (9 HwP)',
                        'gepolstert' => 'Gepolstert (9 HwP)',
                        'geölt' => 'Geölt (9 HwP)',
                        'genietet' => 'Genietet (9 HwP)',
                        'mit holster' => 'mit Holster (9 HwP)',
                    ])
                    ->reactive(),
                ]),
            ])
            ->visible(fn (callable $get) => in_array($get('item_type'), ['Rüstung'])),
            Section::make('Talisman')
            ->description('Werte des Talisman wählen')
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
                    Textinput::make('traglast')
                    ->label('Traglast')
                    ->numeric()
                    ->default(1)
                    ->disabled()
                    ->dehydrated(),
                ]),
                Grid::make(3)
                ->schema([
                    Textinput::make('rs_arcan')
                    ->label('Rüstungsschutz Arkan')
                    ->numeric(),
                    Textinput::make('rs_chaos')
                    ->label('Rüstungsschutz Chaos')
                    ->numeric(),
                    Textinput::make('rs_spirit')
                    ->label('Rüstungsschutz Spirituell')
                    ->numeric(),
                ]),
                Grid::make(2)
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
                    Select::make('ts_erweiterungen')
                    ->label('Erweiterungen')
                    ->multiple()
                    ->options([
                        'der fokussierung' => 'der fokussierung (3 HwP)',
                        'der konzentration' => 'der konzentration (5 HwP)',
                        'der willenskraft' => 'der willenskraft (5 HwP)',
                        'der kommunikation' => 'der kommunikation (5 HwP)',
                        'der wahrnehmung' => 'der wahrnehmung (5 HwP)',
                        'der widersprüche' => 'der widersprüche (7 HwP)',
                        'des nachklangs' => 'des nachklangs (7 HwP)',
                        'der ordnung' => 'der ordnung (7 HwP)',
                        'der ruhe' => 'der ruhe (7 HwP)',
                        'der geduld' => 'der geduld (7 HwP)',
                        'der loyalität' => 'der loyalität (9 HwP)',
                        'der klarheit' => 'der klarheit (9 HwP)',
                        'der furchtlosigkeit' => 'der furchtlosigkeit (9 HwP)',
                        'der vielseitigkeit' => 'der vielseitigkeit (9 HwP)',
                        'der reflektion ' => 'der reflektion  (3 HwP)',
                        'der meditation ' => 'der meditation  (3 HwP)',
                    ])
                    ->reactive(),
                ]),
            ])
            ->visible(fn (callable $get) => in_array($get('item_type'), ['Talisman'])),
            Section::make('Schild')
            ->description('Werte des Schild wählen')
            ->schema([
                Textinput::make('hwp')
                ->label('Handwerkspunkte')
                ->numeric()
                ->step(1)
                ->minValue(1)
                ->maxValue(99),
                Textinput::make('schild_verteidigung')
                ->label('Verteidigungswert')
                ->numeric()
                ->step(1)
                ->minvalue(1)
                ->maxvalue(99),
                Grid::make(3)
                ->schema([
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
                Grid::make(2)
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
                    Select::make('ts_erweiterungen')
                    ->label('Erweiterungen')
                    ->options([
                        'der fokussierung' => 'der fokussierung (3 HwP)',
                        'der konzentration' => 'der konzentration (5 HwP)',
                        'der willenskraft' => 'der willenskraft (5 HwP)',
                        'der kommunikation' => 'der kommunikation (5 HwP)',
                        'der wahrnehmung' => 'der wahrnehmung (5 HwP)',
                        'der widersprüche' => 'der widersprüche (7 HwP)',
                        'des nachklangs' => 'des nachklangs (7 HwP)',
                        'der ordnung' => 'der ordnung (7 HwP)',
                        'der ruhe' => 'der ruhe (7 HwP)',
                        'der geduld' => 'der geduld (7 HwP)',
                        'der loyalität' => 'der loyalität (9 HwP)',
                        'der klarheit' => 'der klarheit (9 HwP)',
                        'der furchtlosigkeit' => 'der furchtlosigkeit (9 HwP)',
                        'der vielseitigkeit' => 'der vielseitigkeit (9 HwP)',
                        'der reflektion ' => 'der reflektion  (3 HwP)',
                        'der meditation ' => 'der meditation  (3 HwP)',
                    ])
                    ->reactive(),
                ]),
            ])
            ->visible(fn (callable $get) => in_array($get('item_type'), ['Schild'])),
            Section::make('schmuckstück')
            ->description('Werte des Schmuckstück wählen')
            ->schema([
                Grid::make(2)
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
                    ])
                ])
            ])
            ->visible(fn (callable $get) => in_array($get('item_type'), ['Schmuckstück'])),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable(),
                Tables\Columns\TextColumn::make('item_type')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListEquipment::route('/'),
            'create' => Pages\CreateEquipment::route('/create'),
            'edit' => Pages\EditEquipment::route('/{record}/edit'),
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


}
