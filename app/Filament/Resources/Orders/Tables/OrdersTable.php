<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID Pedido')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->searchable(),

                TextColumn::make('customer_address')
                    ->label('Dirección de Envío')
                    ->limit(40)
                    ->searchable(),

                TextColumn::make('total_amount')
                    ->label('Total cobrado')
                    ->money('EUR')
                    ->sortable(),

                // Badge dinámico según el estado logístico del paquete
                BadgeColumn::make('status')
                    ->label('Estado Logístico')
                    ->colors([
                        'danger'  => 'pendiente',
                        'warning' => 'confirmado',
                        'info'    => 'enviado',
                        'success' => 'recibido',
                    ]),

                TextColumn::make('gift_name')
                    ->label('Regalo Otorgado')
                    ->placeholder('Sin Regalo'),

                TextColumn::make('created_at')
                    ->label('Fecha Pedido')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filtrar Estado')
                    ->options([
                        'pendiente'  => 'Pendiente',
                        'confirmado' => 'Confirmado',
                        'enviado'    => 'Enviado',
                        'recibido'   => 'Recibido',
                    ]),
            ])
            ->actions([
                // Botones rápidos en fila para cambiar de estado al instante
                Action::make('set_enviado')
                    ->label('Marcar Enviado')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn(Order $record) => $record->status === 'pendiente' || $record->status === 'confirmado')
                    ->action(fn(Order $record) => $record->update(['status' => 'enviado'])),

                Action::make('set_recibido')
                    ->label('Entregado')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(Order $record) => $record->status === 'enviado')
                    ->action(fn(Order $record) => $record->update(['status' => 'recibido'])),

                EditAction::make(),
            ]);
    }
}
