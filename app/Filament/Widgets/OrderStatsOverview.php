<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsOverview extends BaseWidget
{
    protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // 1. Calculamos la facturación total de Supabase
        $totalRevenue = Order::sum('total_amount');

        // 2. Contamos el volumen total de pedidos acumulados
        $totalOrders = Order::count();

        // 3. Controlamos cuántos paquetes tenemos atascados pendientes por enviar
        $pendingOrders = Order::where('status', 'pendiente')->count();

        return [
            // Tarjeta A: Facturación (Morada/Verde según estado)
            Stat::make('Facturación Bruta', number_format($totalRevenue, 2) . '€')
                ->description('Ingresos totales acumulados')
                ->descriptionIcon('heroicon-m-currency-euro')
                ->color('success'), // Color verde para el beneficio

            // Tarjeta B: Volumen de pedidos
            Stat::make('Pedidos Registrados', $totalOrders)
                ->description('Histórico total de la tienda')
                ->descriptionIcon('heroicon-m-shopping-bag'),

            // Tarjeta C: Alerta de logística
            Stat::make('Pedidos Pendientes', $pendingOrders)
                ->description($pendingOrders > 0 ? '¡Tienes trabajo por enviar!' : 'Catálogo al día')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingOrders > 0 ? 'danger' : 'success'), // Si hay > 0 se pone en rojo de alerta
        ];
    }
}