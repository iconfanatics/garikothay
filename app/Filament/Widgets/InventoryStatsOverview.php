<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class InventoryStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $totalProducts = Product::count();
        $totalStock = (int) Product::sum('stock_quantity');
        $reservedStock = (int) Product::sum('reserved_stock');
        $availableStock = $totalStock - $reservedStock;
        
        $inStockCount = Product::where('stock_quantity', '>', 0)->count();
        $outOfStockCount = Product::where('stock_quantity', '<=', 0)->count();
        $lowStockCount = Product::whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->where('stock_quantity', '>', 0)
            ->count();
            
        $preOrderCount = Product::where('is_preorder', true)->count();
        
        $inventoryValue = (float) Product::sum(DB::raw('stock_quantity * IFNULL(cost_price, 0)'));

        return [
            Stat::make("Total Products", number_format($totalProducts))
                ->description('All products in catalog')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('primary')
                ->url(route('filament.admin.resources.products.index')),
                
            Stat::make("Total Stock", number_format($totalStock))
                ->description('Total physical items in warehouse')
                ->descriptionIcon('heroicon-m-inbox-stack')
                ->color('info')
                ->url(route('filament.admin.resources.products.index')),
                
            Stat::make("Available Stock", number_format($availableStock))
                ->description('Ready for new orders')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->url(route('filament.admin.resources.products.index', ['tableFilters' => ['stock_level' => ['value' => 'available_stock']]])),
                
            Stat::make("Reserved Stock", number_format($reservedStock))
                ->description('Booked but not shipped')
                ->descriptionIcon('heroicon-m-lock-closed')
                ->color('warning')
                ->url(route('filament.admin.resources.products.index', ['tableFilters' => ['stock_level' => ['value' => 'reserved_stock']]])),
                
            Stat::make("In Stock Products", number_format($inStockCount))
                ->description('Products currently available')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->url(route('filament.admin.resources.products.index', ['tableFilters' => ['stock_level' => ['value' => 'in_stock']]])),
                
            Stat::make("Pre-Order Products", number_format($preOrderCount))
                ->description('Available for pre-order')
                ->descriptionIcon('heroicon-m-clock')
                ->color('primary')
                ->url(route('filament.admin.resources.products.index', ['tableFilters' => ['is_preorder' => ['value' => true]]])),
                
            Stat::make("Low Stock Products", number_format($lowStockCount))
                ->description('Running out soon')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning')
                ->url(route('filament.admin.resources.products.index', ['tableFilters' => ['stock_level' => ['value' => 'low_stock']]])),
                
            Stat::make("Out of Stock Products", number_format($outOfStockCount))
                ->description('Currently unavailable')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger')
                ->url(route('filament.admin.resources.products.index', ['tableFilters' => ['stock_level' => ['value' => 'out_of_stock']]])),
                
            Stat::make("Inventory Value", '৳' . number_format($inventoryValue, 2))
                ->description('Total value based on supplier price')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
