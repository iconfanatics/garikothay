<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Payment;

class PaymentStatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Transactions', Payment::count()),
            Stat::make('Successful Payments', Payment::where('status', 'completed')->count())
                ->color('success'),
            Stat::make('Pending Payments', Payment::where('status', 'pending')->count())
                ->color('warning'),
            Stat::make('Failed Payments', Payment::where('status', 'failed')->count())
                ->color('danger'),
            Stat::make('Total Refunds', Payment::where('status', 'refunded')->sum('amount'))
                ->color('secondary'),
            Stat::make('Total Revenue', '৳' . number_format(Payment::where('status', 'completed')->sum('amount'), 2))
                ->color('success'),
        ];
    }
}
