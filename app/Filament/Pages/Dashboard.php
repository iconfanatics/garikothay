<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminWelcome;
use App\Filament\Widgets\LatestOrdersTable;
use App\Filament\Widgets\LowStockAlert;
use App\Filament\Widgets\RevenueChart;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = "heroicon-o-home";
    protected static ?string $navigationLabel = "Dashboard";
    protected static ?int $navigationSort = -10;
    protected static string $view = 'filament.pages.main-dashboard';

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return \App\Models\Setting::get('site_name', 'Garikothay') . ' Dashboard';
    }

    // Disable the default getWidgets to prevent double rendering if we call it
    public function getWidgets(): array
    {
        return [];
    }

    public function getOverviewWidgets(): array
    {
        return [
            AdminWelcome::class,
            StatsOverview::class,
            RevenueChart::class,
            LatestOrdersTable::class,
            \App\Filament\Widgets\RecentActivities::class,
        ];
    }

    public function getInventoryWidgets(): array
    {
        return [
            \App\Filament\Widgets\InventoryStatsOverview::class,
            LowStockAlert::class,
            \App\Filament\Widgets\TopSellingProducts::class,
            \App\Filament\Widgets\OutOfStockProducts::class,
            \App\Filament\Widgets\TopSuppliers::class,
        ];
    }

    public function getMarketingWidgets(): array
    {
        return [
            \App\Filament\Widgets\CustomerAnalytics::class,
            \App\Filament\Widgets\CouponPerformance::class,
            \App\Filament\Widgets\BannerStatsOverview::class,
            \App\Filament\Widgets\ReviewStatsOverview::class,
            \App\Filament\Widgets\ReviewRatingChart::class,
        ];
    }

    public function getPaymentWidgets(): array
    {
        return [
            \App\Filament\Widgets\PaymentStatsOverview::class,
            \App\Filament\Widgets\PaymentRevenueChart::class,
            \App\Filament\Widgets\PaymentMethodChart::class,
            \App\Filament\Widgets\RevenueAnalytics::class,
        ];
    }
    
    public function getContentWidgets(): array
    {
        return [
            \App\Filament\Widgets\BlogStatsOverview::class,
        ];
    }

    public function getColumns(): int|string|array
    {
        return [
            "default" => 1,
            "md" => 2,
            "xl" => 12,
        ];
    }
}
