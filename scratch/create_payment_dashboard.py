import os

def create_file(filepath, content):
    with open(filepath, 'w') as f:
        f.write(content)

# 1. Update PaymentDashboard page
dashboard_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Pages/PaymentDashboard.php"
dashboard_content = """<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\PaymentStatsOverview;
use App\Filament\Widgets\PaymentRevenueChart;
use App\Filament\Widgets\PaymentMethodChart;

class PaymentDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Payment Management';
    protected static ?string $title = 'Payment Dashboard';
    protected static string $view = 'filament.pages.payment-dashboard';
    
    protected function getHeaderWidgets(): array
    {
        return [
            PaymentStatsOverview::class,
            PaymentRevenueChart::class,
            PaymentMethodChart::class,
        ];
    }
}
"""
create_file(dashboard_file, dashboard_content)

# 2. Create View for PaymentDashboard
view_dir = "/home/sany/Desktop/mmm/e-commerce/resources/views/filament/pages"
os.makedirs(view_dir, exist_ok=True)
view_file = f"{view_dir}/payment-dashboard.blade.php"
view_content = """<x-filament-panels::page>
    @if (count($this->getHeaderWidgets()))
        <x-filament-widgets::widgets
            :columns="$this->getHeaderWidgetsColumns()"
            :data="$this->getWidgetData()"
            :widgets="$this->getHeaderWidgets()"
        />
    @endif
</x-filament-panels::page>
"""
create_file(view_file, view_content)

# 3. Create PaymentStatsOverview widget
stats_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Widgets/PaymentStatsOverview.php"
stats_content = """<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Payment;

class PaymentStatsOverview extends BaseWidget
{
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
"""
create_file(stats_file, stats_content)


# 4. Create PaymentRevenueChart widget
revenue_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Widgets/PaymentRevenueChart.php"
revenue_content = """<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Payment;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PaymentRevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue Overview (Last 30 Days)';
    
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
"""
create_file(revenue_file, revenue_content)


# 5. Create PaymentMethodChart widget
method_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Widgets/PaymentMethodChart.php"
method_content = """<?php

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
"""
create_file(method_file, method_content)

print("Payment Dashboard and Widgets created!")
