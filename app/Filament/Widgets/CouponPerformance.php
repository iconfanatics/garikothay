<?php

namespace App\Filament\Widgets;

use App\Models\Coupon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class CouponPerformance extends BaseWidget
{
    protected int | string | array $columnSpan = ['default' => 'full', 'md' => 1, 'xl' => 6];
    protected static ?int $sort = 9;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Coupon::query()
                    ->where('is_active', true)
                    ->withCount(['orders as used_this_month' => function (Builder $query) {
                        $query->whereMonth('created_at', now()->month)
                              ->whereYear('created_at', now()->year);
                    }])
                    ->orderByDesc('used_this_month')
                    ->limit(5)
            )
            ->heading('Coupon Performance')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Coupon Code')
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('used_this_month')
                    ->label('Used This Month')
                    ->sortable(),
                Tables\Columns\TextColumn::make('used_count')
                    ->label('Total Uses')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
