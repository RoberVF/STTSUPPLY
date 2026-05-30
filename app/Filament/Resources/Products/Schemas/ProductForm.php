<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Nombre del Producto')
                    ->required()
                    ->lazy()
                    ->afterStateUpdated(fn(string $state, Set $set) => $set('slug', \Illuminate\Support\Str::slug($state))),

                TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required(),

                Grid::make(3)
                    ->schema([
                        TextInput::make('league')
                            ->label('Liga')
                            ->required(),

                        TextInput::make('team')
                            ->label('Equipo'),

                        Select::make('category')
                            ->label('Tipo de Ropa')
                            ->required()
                            ->options([
                                'Shirt'               => 'Camisetas (Shirt)',
                                'Kids'                => 'Niños (Kids)',
                                'Baby'                => 'Bebés (Baby)',
                                'Jacket Tracksuit'    => 'Chándal Chaqueta',
                                'Half Pull Tracksuit' => 'Chándal Half Pull',
                                'Windbreaker'         => 'Cortavientos',
                                'Hoodie'              => 'Sudaderas (Hoodie)',
                                'Polo'                => 'Polos (Polo)',
                                'Long Sleeve'         => 'Manga Larga (Long Sleeve)',
                                'Short Pants'         => 'Pantalones Cortos (Short Pants)',
                            ]),
                    ]),

                Grid::make(2)
                    ->schema([
                        TextInput::make('provider_price')
                            ->label('Precio Proveedor (€)')
                            ->numeric()
                            ->required(),

                        TextInput::make('selling_price')
                            ->label('Precio Venta (€)')
                            ->numeric()
                            ->required(),
                    ]),

                TagsInput::make('images')
                    ->label('URLs de las Imágenes')
                    ->placeholder('Añade URLs de fotos'),

                Toggle::make('is_active')
                    ->label('Producto Activo')
                    ->default(true),

                Toggle::make('is_featured')
                    ->label('Producto Destacado')
                    ->default(false),
            ]);
    }
}
