<?php

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
    
    protected static string $view = 'filament.pages.review-dashboard';
    
    protected function getHeaderWidgets(): array
    {
        return [
            ReviewStatsOverview::class,
            ReviewRatingChart::class,
        ];
    }
}
