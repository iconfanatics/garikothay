<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Banner;

class BannerStatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Active Banners', Banner::where('is_active', true)->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))->count())
                ->description('Currently showing')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->url(route('filament.admin.resources.banners.index')),
            Stat::make('Scheduled Banners', Banner::where('is_active', true)->whereNotNull('starts_at')->where('starts_at', '>', now())->count())
                ->description('Upcoming')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info')
                ->url(route('filament.admin.resources.banners.index')),
            Stat::make('Inactive/Expired Banners', Banner::where('is_active', false)->orWhere(fn ($q) => $q->whereNotNull('expires_at')->where('expires_at', '<', now()))->count())
                ->description('Needs attention')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger')
                ->url(route('filament.admin.resources.banners.index')),
        ];
    }
}
