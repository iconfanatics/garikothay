<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Blog;

class BlogStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Blogs', Blog::count())
                ->description('All time')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
            Stat::make('Published Blogs', Blog::where('is_published', true)->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))->count())
                ->description('Live on site')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Drafts', Blog::where('is_published', false)->count())
                ->description('Unpublished')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color('warning'),
        ];
    }
}
