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
            )
            ->heading('Top Selling Products')
            ->columns([
                Tables\Columns\TextColumn::make('translations.name')
                    ->label('Product Name')
                    ->formatStateUsing(fn ($record) => $record->translations->firstWhere('locale', 'en')?->name ?? 'N/A')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('translations', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU'),
                Tables\Columns\TextColumn::make('order_items_sum_quantity')
                    ->label('Sold Quantity')
                    ->badge()
                    ->color('success'),
            ])
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25]);
    }
}
