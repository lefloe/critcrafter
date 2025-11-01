<?php

namespace App\Filament\Resources\Equipment\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EquipmentTable
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
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('quality')
                    ->searchable(),
                TextColumn::make('item_type')
                    ->searchable(),
                TextColumn::make('hwp')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('waffengattung')
                    ->searchable(),
                TextColumn::make('attackvalue')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('trefferwuerfel')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('traglast')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('passive_verteidigung')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('schild_verteidigung')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rs_schnitt')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rs_stumpf')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rs_stich')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rs_elementar')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kontrollwiderstand')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rs_arcan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rs_chaos')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rs_spirit')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('enchantment_qs')
                    ->searchable(),
                TextColumn::make('character_id')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('equipped')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
