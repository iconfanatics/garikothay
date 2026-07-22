<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentMethodChart extends ChartWidget
{
    protected static ?string $heading = 'Transactions by Method';
    
    protected static bool $isLazy = false;

    protected function getData(): array
    {
        $methods = Payment::select('payment_method', DB::raw('count(*) as total'))
            ->groupBy('payment_method')
            ->get();

        $labels = [];
        $data = [];

        foreach ($methods as $method) {
            $labels[] = strtoupper($method->payment_method);
            $data[] = $method->total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Transactions',
                    'data' => $data,
                    'backgroundColor' => ['#34d399', '#f87171', '#60a5fa', '#fbbf24', '#c084fc'],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
