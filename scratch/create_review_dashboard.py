import os

def create_file(filepath, content):
    os.makedirs(os.path.dirname(filepath), exist_ok=True)
    with open(filepath, 'w') as f:
        f.write(content)

# 1. Update ReviewDashboard page
dashboard_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Pages/ReviewDashboard.php"
dashboard_content = """<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\ReviewStatsOverview;
use App\Filament\Widgets\ReviewRatingChart;

class ReviewDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?string $navigationGroup = 'Catalog';
    protected static ?string $title = 'Review Dashboard';
    protected static ?int $navigationSort = 5;
    
    // We explicitly set the view to the default filament page view so it renders header widgets automatically
    protected static string $view = 'filament-panels::pages.dashboard';
    
    protected function getHeaderWidgets(): array
    {
        return [
            ReviewStatsOverview::class,
            ReviewRatingChart::class,
        ];
    }
}
"""
create_file(dashboard_file, dashboard_content)


# 2. Create ReviewStatsOverview widget
stats_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Widgets/ReviewStatsOverview.php"
stats_content = """<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Review;

class ReviewStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Reviews', Review::count())
                ->description('All time reviews')
                ->descriptionIcon('heroicon-m-chat-bubble-left-ellipsis')
                ->color('primary'),
            Stat::make('Pending Reviews', Review::where('is_approved', false)->count())
                ->description('Needs moderation')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Approved Reviews', Review::where('is_approved', true)->count())
                ->description('Visible on site')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}
"""
create_file(stats_file, stats_content)


# 3. Create ReviewRatingChart widget
chart_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Widgets/ReviewRatingChart.php"
chart_content = """<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewRatingChart extends ChartWidget
{
    protected static ?string $heading = 'Rating Breakdown';
    protected static ?string $maxHeight = '300px';
    protected static bool $isLazy = false;

    protected function getData(): array
    {
        $ratings = Review::select('rating', DB::raw('count(*) as total'))
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get();

        $labels = [];
        $data = [];

        // Pre-fill 5 to 1
        $map = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        foreach ($ratings as $r) {
            $map[$r->rating] = $r->total;
        }

        foreach ($map as $stars => $count) {
            $labels[] = $stars . ' Stars';
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Number of Reviews',
                    'data' => $data,
                    'backgroundColor' => ['#10b981', '#34d399', '#fbbf24', '#f87171', '#ef4444'],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    
    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y', // Makes it a horizontal bar chart
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
"""
create_file(chart_file, chart_content)

print("Review Dashboard and Widgets created!")
