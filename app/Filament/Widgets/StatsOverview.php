<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $today = Carbon::today();

        $todayOrdersCount = Order::whereDate('created_at', $today)->count();

        $todayRevenue = Order::whereDate('created_at', $today)
            ->where('payment_status', PaymentStatus::Paid)
            ->sum('total');

        $yesterdayRevenue = Order::whereDate('created_at', $today->copy()->subDay())
            ->where('payment_status', PaymentStatus::Paid)
            ->sum('total');

        $revenueChange = $yesterdayRevenue > 0
            ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1)
            : 0;

        $totalCustomers = User::count();

        $newCustomersThisMonth = User::whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->count();

        $pendingOrders = Order::where('status', OrderStatus::Pending)->count();

        $lastWeekPending = Order::where('status', OrderStatus::Pending)
            ->where('created_at', '<', $today->copy()->subWeek())
            ->count();

        $todayRevenueNet = Order::whereDate('created_at', $today)
            ->where('payment_status', PaymentStatus::Paid)
            ->sum(\Illuminate\Support\Facades\DB::raw('subtotal - discount_amount'));

        $todayCogs = \App\Models\OrderItem::whereHas('order', function ($q) use ($today) {
                $q->whereDate('created_at', $today)->where('payment_status', PaymentStatus::Paid);
            })
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->sum(\Illuminate\Support\Facades\DB::raw('order_items.quantity * IFNULL(products.cost_price, 0)'));
            
        $todayProfit = $todayRevenueNet - $todayCogs;

        $totalRevenueNet = Order::where('payment_status', PaymentStatus::Paid)
            ->sum(\Illuminate\Support\Facades\DB::raw('subtotal - discount_amount'));

        $totalCogs = \App\Models\OrderItem::whereHas('order', function ($q) {
                $q->where('payment_status', PaymentStatus::Paid);
            })
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->sum(\Illuminate\Support\Facades\DB::raw('order_items.quantity * IFNULL(products.cost_price, 0)'));
            
        $totalProfit = $totalRevenueNet - $totalCogs;

        $totalRevenue = Order::where('payment_status', PaymentStatus::Paid)->sum('total');

        $totalProducts = \App\Models\Product::count();
        $inStockProducts = \App\Models\Product::where('stock_quantity', '>', 0)->count();
        $outOfStockProducts = \App\Models\Product::where('stock_quantity', '<=', 0)->count();
        $lowStockProducts = \App\Models\Product::whereColumn('stock_quantity', '<=', 'low_stock_threshold')->where('stock_quantity', '>', 0)->count();
        
        $completedOrders = Order::where('status', OrderStatus::Delivered)->count();
        $cancelledOrders = Order::where('status', OrderStatus::Cancelled)->count();
        $returnedOrders = Order::where('status', OrderStatus::Returned)->count();

        return [
            Stat::make("Total Revenue", '৳' . number_format((float) $totalRevenue, 2))
                ->description('All time total revenue')
                ->descriptionIcon('heroicon-m-currency-bangladeshi')
                ->color('success'),
                
            Stat::make("Total Products", (string) $totalProducts)
                ->description("{$inStockProducts} In Stock, {$outOfStockProducts} Out of Stock")
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('info'),
                
            Stat::make("Completed Orders", (string) $completedOrders)
                ->description('Successfully delivered')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make("Cancelled Orders", (string) $cancelledOrders)
                ->description('Cancelled by admin/customer')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
                
            Stat::make("Returned Orders", (string) $returnedOrders)
                ->description('Returned products')
                ->descriptionIcon('heroicon-m-arrow-uturn-left')
                ->color('warning')
                ->url(route('filament.admin.resources.orders.index', ['tableFilters' => ['status' => ['value' => OrderStatus::Returned->value]]])),
                
            Stat::make("Low Stock Products", (string) $lowStockProducts)
                ->description('Running out soon')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning')
                ->url(route('filament.admin.resources.products.index', ['tableFilters' => ['stock_level' => ['value' => 'low_stock']]])),
                
            Stat::make("Out of Stock Products", (string) $outOfStockProducts)
                ->description('Currently unavailable')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger')
                ->url(route('filament.admin.resources.products.index', ['tableFilters' => ['stock_level' => ['value' => 'out_of_stock']]])),
        ];
    }
}
