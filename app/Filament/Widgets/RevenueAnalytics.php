<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class RevenueAnalytics extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        // Revenue (All Paid Orders)
        $totalRevenue = Order::where('payment_status', PaymentStatus::Paid)->sum('total');

        // Discount Amount (All Orders)
        $totalDiscount = Order::sum('discount_amount');

        // Refund Amount (Orders with status Refunded)
        $totalRefunded = Order::where('status', OrderStatus::Refunded)->sum('total');

        // Profit (Revenue Net - COGS)
        $totalRevenueNet = Order::where('payment_status', PaymentStatus::Paid)
            ->sum(DB::raw('subtotal - discount_amount'));

        $totalCogs = OrderItem::whereHas('order', function ($q) {
                $q->where('payment_status', PaymentStatus::Paid);
            })
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->sum(DB::raw('order_items.quantity * IFNULL(products.cost_price, 0)'));
            
        $totalProfit = $totalRevenueNet - $totalCogs;

        return [
            Stat::make('Gross Revenue', '৳' . number_format((float) $totalRevenue, 2))
                ->description('Total revenue from paid orders')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),

            Stat::make('Net Profit', '৳' . number_format((float) $totalProfit, 2))
                ->description('Revenue minus cost of goods sold')
                ->descriptionIcon('heroicon-m-chart-pie')
                ->color('success'),

            Stat::make('Total Refunds', '৳' . number_format((float) $totalRefunded, 2))
                ->description('Total amount refunded to customers')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('danger'),

            Stat::make('Total Discounts Given', '৳' . number_format((float) $totalDiscount, 2))
                ->description('Total discount amount applied')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('info'),
        ];
    }
}
