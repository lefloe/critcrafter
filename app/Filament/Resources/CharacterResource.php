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



class CharacterResource extends Resource
{
    protected static ?string $model = Character::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->statePath('')
                    ->tabs([
                        Tabs\Tab::make('Tab 1')
                            ->schema([
                                TextInput::make('name')
                                                ->label('Name')
                                                ->required()
                                                ->maxLength(255),
                                        // TextInput::make('test')
                                        //          ->label('Test'),
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
                                Textarea::make('description')
                                ->label('Description')
                                ->required()
                                ->maxLength(800),
                            ]),
                        Tabs\Tab::make('Tab 3')
                            ->schema([
                                Select::make('race')
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
                                TextInput::make('experience-level')
                                ->numeric()
                                ->step(1)
                                ->maxValue(22)
                                ->minValue(1)
                            ]),
                        ])
                        ->columnSpanFull()
            ]);


                
            //     Forms\Components\Wizard::make([
            //         Forms\Components\Wizard\Step::make('Step 1')
            //         ->schema([
                        
            //         ]),
                    
            //     ])->columnSpanFull()
            // ]);
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
