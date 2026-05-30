<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Grouping\Group;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id')
                    ->label('ID del Pedido')
                    ->disabled() // No se toca, es autoincremental
                    ->dehydrated(),

                Select::make('status')
                    ->label('Estado Logístico')
                    ->required()
                    ->native(false)
                    ->options([
                        'pendiente'  => '🔴 Pendiente de Confirmar',
                        'confirmado' => '🟡 Pago/Pedido Confirmado',
                        'enviado'    => '🔵 Enviado (En reparto por Canarias)',
                        'recibido'   => '🟢 Entregado al Cliente',
                    ]),

                TextInput::make('customer_name')
                    ->label('Nombre del Cliente')
                    ->required(),

                Textarea::make('customer_address')
                    ->label('Dirección de Entrega Completa')
                    ->required()
                    ->rows(3),

                TextInput::make('total_amount')
                    ->label('Total del Pedido')
                    ->numeric()
                    ->prefix('€')
                    ->disabled() // Protegemos el total para que no se altere la caja a lo loco
                    ->dehydrated(),

                TextInput::make('gift_name')
                    ->label('Regalo Desbloqueado')
                    ->disabled()
                    ->placeholder('Ninguno (Menos de 5 prendas)')
                    ->dehydrated(),

                Placeholder::make('created_at')
                    ->label('Fecha de Registro')
                    ->content(fn($record): string => $record?->created_at ? $record->created_at->format('d/m/Y H:i') : '-'),

                Section::make('Prendas Incluidas en este Pedido')
                    ->description('Histórico congelado de los artículos solicitados por el cliente.')
                    ->collapsible()
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items') // El nombre de la relación en tu modelo Order
                            ->schema([
                                TextInput::make('title')
                                    ->label('Modelo Jersey')
                                    ->disabled()
                                    ->columnSpan(3),

                                TextInput::make('team')
                                    ->label('Equipo/Liga')
                                    ->disabled()
                                    ->columnSpan(1),

                                TextInput::make('quantity')
                                    ->label('Cant.')
                                    ->numeric()
                                    ->disabled()
                                    ->columnSpan(1),

                                TextInput::make('price')
                                    ->label('Precio Unitario')
                                    ->numeric()
                                    ->prefix('€')
                                    ->disabled()
                                    ->columnSpan(1),
                            ])
                            ->columns(6)
                            ->disableItemCreation() // Desactivamos botones molestos para que sea solo de lectura
                            ->disableItemDeletion()
                            ->disableItemMovement()
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
