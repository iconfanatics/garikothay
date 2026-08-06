<?php

namespace App\Filament\Widgets;

use App\Models\Supplier;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopSuppliers extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 8;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Supplier::query()
                    ->select('suppliers.id', 'suppliers.name')
                    ->join('products', 'products.supplier_id', '=', 'suppliers.id')
                    ->join('order_items', 'order_items.product_id', '=', 'products.id')
                    ->selectRaw('COUNT(DISTINCT order_items.order_id) as total_orders')
                    ->selectRaw('SUM(order_items.total_price) as total_sales')
                    ->groupBy('suppliers.id', 'suppliers.name')
                    ->orderByDesc('total_sales')
            )
            ->heading('Top Suppliers')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Supplier Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_orders')
                    ->label('Total Orders')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_sales')
                    ->label('Total Sales')
                    ->money('BDT')
                    ->sortable(),
            ])
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25]);
    }
}
