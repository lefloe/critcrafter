<?php

namespace App\Filament\Resources\Characters\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CharactersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('race')
                    ->searchable(),
                TextColumn::make('wesen')
                    ->searchable(),
                TextColumn::make('leiteigenschaft1')
                    ->searchable(),
                TextColumn::make('leiteigenschaft2')
                    ->searchable(),
                TextColumn::make('main_stat_value')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('ko_toggle')
                    ->boolean(),
                TextColumn::make('archetype')
                    ->searchable(),
                TextColumn::make('ko')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('st')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ag')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ge')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('we')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('in')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mu')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ch')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('leps')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tragkraft')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('geschwindigkeit')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('handwerksbonus')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kontrollwiderstand')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('initiative')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('verteidigung')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('seelenpunkte')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nw_gattung')
                    ->searchable(),
                TextColumn::make('nw_quality')
                    ->searchable(),
                TextColumn::make('nw_aw')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nw_vw')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nw_tw')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('xp')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
