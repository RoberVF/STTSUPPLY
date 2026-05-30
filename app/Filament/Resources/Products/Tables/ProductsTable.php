<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {

        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('Foto')
                    ->state(function ($record) {
                        // Cogemos la primera foto del array JSON de imágenes
                        return $record->images[0] ?? null;
                    })
                    ->circular(),

                TextColumn::make('title')
                    ->label('Producto')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('league')
                    ->label('Liga')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('team')
                    ->label('Equipo')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('category')
                    ->label('Categoría')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('selling_price')
                    ->label('P. Venta')
                    ->money('EUR')
                    ->color('success')
                    ->weight('bold')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Activo'),

                ToggleColumn::make('is_featured')
                    ->label('Destacado'),
            ])
            ->filters([
                SelectFilter::make('league')
                    ->label('Filtrar por Liga')
                    ->options([
                        'La Liga' => 'La Liga',
                        'Serie A' => 'Serie A',
                        'Premier League' => 'Premier League',
                        'Ligue 1' => 'Ligue 1',
                        'Bundesliga' => 'Bundesliga',
                        'Retro' => 'Retro',
                    ]),

                SelectFilter::make('category')
                    ->preload()
                    ->label('Tipo de Ropa')
                    ->options(fn() => \App\Models\Product::distinct()->pluck('category', 'category')->toArray()),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
