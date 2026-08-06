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
            Stat::make('Total Transactions', Payment::count())
                ->url(route('filament.admin.resources.payments.index')),
            Stat::make('Successful Payments', Payment::where('status', 'completed')->count())
                ->color('success')
                ->url(route('filament.admin.resources.payments.index', ['tableFilters' => ['status' => ['value' => 'completed']]])),
            Stat::make('Pending Payments', Payment::where('status', 'pending')->count())
                ->color('warning')
                ->url(route('filament.admin.resources.payments.index', ['tableFilters' => ['status' => ['value' => 'pending']]])),
            Stat::make('Failed Payments', Payment::where('status', 'failed')->count())
                ->color('danger')
                ->url(route('filament.admin.resources.payments.index', ['tableFilters' => ['status' => ['value' => 'failed']]])),
            Stat::make('Total Refunds', '৳' . number_format(Payment::where('status', 'refunded')->sum('amount'), 2))
                ->color('secondary')
                ->url(route('filament.admin.resources.payments.index', ['tableFilters' => ['status' => ['value' => 'refunded']]])),
            Stat::make('Total Revenue', '৳' . number_format(Payment::where('status', 'completed')->sum('amount'), 2))
                ->color('success')
                ->url(route('filament.admin.resources.payments.index', ['tableFilters' => ['status' => ['value' => 'completed']]])),
        ];
    }
}
