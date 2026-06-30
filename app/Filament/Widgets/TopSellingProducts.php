<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopSellingProducts extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 6;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->withSum('orderItems', 'quantity')
                    ->having('order_items_sum_quantity', '>', 0)
                    ->orderByDesc('order_items_sum_quantity')
                    ->limit(5)
            )
            ->heading('Top Selling Products')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Product Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU'),
                Tables\Columns\TextColumn::make('order_items_sum_quantity')
                    ->label('Sold Quantity')
                    ->badge()
                    ->color('success'),
            ])
            ->paginated(false);
    }
}
