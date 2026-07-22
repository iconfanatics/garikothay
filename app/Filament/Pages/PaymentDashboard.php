<?php

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
    
    protected function getHeaderWidgets(): array
    {
        return [
            PaymentStatsOverview::class,
            PaymentRevenueChart::class,
            PaymentMethodChart::class,
        ];
    }
}
