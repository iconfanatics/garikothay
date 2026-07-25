<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\InventoryStatsOverview;

class InventoryDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string $view = 'filament.pages.inventory-dashboard';
    
    protected static ?string $navigationGroup = 'Catalog';
    
    protected static ?int $navigationSort = 2;
    
    protected static ?string $title = 'Inventory Dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            InventoryStatsOverview::class,
        ];
    }
}
