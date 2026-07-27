<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Payment;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PaymentRevenueChart extends ChartWidget
{
    protected int | string | array $columnSpan = ['default' => 'full', 'md' => 1, 'xl' => 6];

    protected static ?string $heading = 'Revenue Overview (Last 30 Days)';

    protected static ?string $maxHeight = '250px';
    
    // Disable lazy loading since this widget is required immediately
    protected static bool $isLazy = false;

    protected function getData(): array
    {
        $data = Trend::query(Payment::where('status', 'completed'))
            ->between(
                start: now()->subDays(30),
                end: now(),
            )
            ->perDay()
            ->sum('amount');

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
