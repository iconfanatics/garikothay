<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Review;

class ReviewStatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Reviews', Review::count())
                ->description('All time reviews')
                ->descriptionIcon('heroicon-m-chat-bubble-left-ellipsis')
                ->color('primary')
                ->url(route('filament.admin.resources.reviews.index')),
            Stat::make('Pending Reviews', Review::where('is_approved', false)->count())
                ->description('Needs moderation')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->url(route('filament.admin.resources.reviews.index', ['tableFilters' => ['is_approved' => ['value' => '0']]])),
            Stat::make('Approved Reviews', Review::where('is_approved', true)->count())
                ->description('Visible on site')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->url(route('filament.admin.resources.reviews.index', ['tableFilters' => ['is_approved' => ['value' => '1']]])),
        ];
    }
}
