<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerAnalytics extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $thisMonth = Carbon::now()->startOfMonth();
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        $newCustomers = User::where('created_at', '>=', $thisMonth)->count();

        $activeCustomers = User::whereHas('orders', fn ($q) => $q->where('created_at', '>=', $thirtyDaysAgo))->count();

        // Customers with more than 1 order
        $returningCustomers = User::has('orders', '>', 1)->count();

        return [
            Stat::make('New Customers (This Month)', (string) $newCustomers)
                ->description('Registered since ' . $thisMonth->format('M j'))
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success')
                ->url(route('filament.admin.resources.customers.index', ['tableFilters' => ['customer_segment' => ['value' => 'new_this_month']]])),

            Stat::make('Active Customers', (string) $activeCustomers)
                ->description('Placed an order in last 30 days')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->url(route('filament.admin.resources.customers.index', ['tableFilters' => ['customer_segment' => ['value' => 'active_last_30_days']]])),

            Stat::make('Returning Customers', (string) $returningCustomers)
                ->description('Customers with more than 1 order')
                ->descriptionIcon('heroicon-m-arrow-path-rounded-square')
                ->color('info')
                ->url(route('filament.admin.resources.customers.index', ['tableFilters' => ['customer_segment' => ['value' => 'returning']]])),
        ];
    }
}
