<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewRatingChart extends ChartWidget
{
    protected int | string | array $columnSpan = 'full';

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
