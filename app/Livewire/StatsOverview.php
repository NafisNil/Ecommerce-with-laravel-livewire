<?php

namespace App\Livewire;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
class StatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '10s';
    protected function getStats(): array
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $todayRevenue = Order::where('payment_status', 'paid')->whereDate('created_at', now())->sum('total');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalCustomers = Customer::count();
        $newCustomers = Customer::whereDate('created_at', now())->count();
        $lowStockProducts = Product::query()->lowStock()->count();
        return [
            Stat::make('Total Revenue', '$ ' . number_format($totalRevenue, 2))
            ->description('Total revenue from paid orders' . ($todayRevenue > 0 ? ' (Today: $' . number_format($todayRevenue, 2) . ')' : ''))
            ->descriptionIcon('heroicon-s-arrow-trending-up')
            ->color('success'),
            Stat::make('Total Orders', $totalOrders)->description('pending orders: ' . $pendingOrders)->descriptionIcon('heroicon-s-clock')->color('danger')->url(route('filament.admin.resources.orders.index')),
            Stat::make('Total Customers', $totalCustomers)->description('new customers: ' . $newCustomers)->descriptionIcon('heroicon-s-user-plus')->color('primary')->url(route('filament.admin.resources.customers.index')),
            Stat::make('Low stock alert', $lowStockProducts)->description('products')->descriptionIcon('heroicon-s-exclamation-triangle')->color('warning')->url(route('filament.admin.resources.products.index')),
        ];
    }
}
