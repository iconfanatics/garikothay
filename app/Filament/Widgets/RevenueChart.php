<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\PaymentStatus;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue Analytics';
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';

    public ?string $filter = 'daily';

    protected function getFilters(): ?array
    {
        return [
            'daily' => 'Daily (Last 30 days)',
            'monthly' => 'Monthly (This Year)',
            'yearly' => 'Yearly (All Time)',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $days   = collect();
        $labels = [];
        $data   = [];

        if ($activeFilter === 'yearly') {
            $years = Order::selectRaw('YEAR(created_at) as year, SUM(total) as revenue')
                ->where('payment_status', PaymentStatus::Paid)
                ->groupBy('year')
                ->orderBy('year')
                ->get();
                
            foreach ($years as $row) {
                $labels[] = (string) $row->year;
                $data[] = round((float) $row->revenue, 2);
            }
        } elseif ($activeFilter === 'monthly') {
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = Carbon::create()->month($i)->format('M');
                $days->put($i, 0);
            }
            
            Order::selectRaw('MONTH(created_at) as month, SUM(total) as revenue')
                ->where('payment_status', PaymentStatus::Paid)
                ->whereYear('created_at', Carbon::now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->each(function ($row) use (&$days) {
                    $days->put($row->month, round((float) $row->revenue, 2));
                });
                
            $data = $days->values()->toArray();
        } else {
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $days->put($date->format('Y-m-d'), 0);
                $labels[] = $date->format('d M');
            }

            Order::selectRaw('DATE(created_at) as date, SUM(total) as revenue')
                ->where('payment_status', PaymentStatus::Paid)
                ->whereDate('created_at', '>=', Carbon::today()->subDays(29))
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->each(function ($row) use (&$days): void {
                    $days->put($row->date, round((float) $row->revenue, 2));
                });

            $data = $days->values()->toArray();
        }

        return [
            'datasets' => [
                [
                    'label'                => 'Revenue (৳)',
                    'data'                 => $data,
                    'borderColor'          => '#16a34a',
                    'backgroundColor'      => 'rgba(22, 163, 74, 0.08)',
                    'fill'                 => true,
                    'tension'              => 0.4,
                    'pointBackgroundColor' => '#16a34a',
                    'pointRadius'          => 3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(context){ return '৳ ' + context.parsed.y.toLocaleString(); }",
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks'       => [
                        'callback' => "function(value){ return '৳ ' + value.toLocaleString(); }",
                    ],
                ],
            ],
        ];
    }
}
