<?php

namespace App\Filament\Resources\AnnouncementResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnnouncementStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Active Announcements', \App\Models\Announcement::where('is_active', true)->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))->count())
                ->description('Currently showing')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Scheduled', \App\Models\Announcement::where('is_active', true)->whereNotNull('starts_at')->where('starts_at', '>', now())->count())
                ->description('Upcoming')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
        ];
    }
}
