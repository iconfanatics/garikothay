<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class OutOfStockProducts extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 7;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->where('stock_quantity', '<=', 0)
                    ->orderBy('id')
                    ->limit(5)
            )
            ->heading('Out of Stock Products')
            ->columns([
                Tables\Columns\TextColumn::make('translations.name')
                    ->label('Product Name')
                    ->formatStateUsing(fn ($record) => $record->name)
                    ->searchable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU'),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->badge()
                    ->color('danger'),
            ])
            ->paginated(false);
    }
}
