<?php

namespace App\Filament\Player\Resources\Characters;

use App\Filament\Player\Resources\Characters\Pages\CreateCharacter;
use App\Filament\Player\Resources\Characters\Pages\EditCharacter;
use App\Filament\Player\Resources\Characters\Pages\ListCharacters;
use App\Filament\Player\Resources\Characters\Pages\ViewCharacter;
use App\Filament\Player\Resources\Characters\Schemas\CharacterForm;
use App\Filament\Player\Resources\Characters\Schemas\CharacterInfolist;
use App\Filament\Player\Resources\Characters\Tables\CharactersTable;
use App\Models\Character;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CharacterResource extends Resource
{
    protected static ?string $model = Character::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CharacterForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CharacterInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CharactersTable::configure($table);
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
            'index' => ListCharacters::route('/'),
            'create' => CreateCharacter::route('/create'),
            'view' => ViewCharacter::route('/{record}'),
            'edit' => EditCharacter::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
