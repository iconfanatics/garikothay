<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrdersTable extends BaseWidget
{
    protected static ?string $heading = 'Latest Orders';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()->with(['user'])
            )
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('BDT'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (OrderStatus $state): string => $state->label())
                    ->color(fn (OrderStatus $state): string => $state->color())
                    ->searchable(query: function (Builder $query, string $search) {
                        $matchedValues = collect(OrderStatus::cases())
                            ->filter(fn ($enum) => stripos($enum->label(), $search) !== false)
                            ->map(fn ($enum) => $enum->value)
                            ->toArray();
                        if (!empty($matchedValues)) {
                            return $query->whereIn('status', $matchedValues);
                        }
                        return $query;
                    }),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Payment')
                    ->formatStateUsing(fn (PaymentStatus $state): string => $state->label())
                    ->color(fn (PaymentStatus $state): string => $state->color())
                    ->searchable(query: function (Builder $query, string $search) {
                        $matchedValues = collect(PaymentStatus::cases())
                            ->filter(fn ($enum) => stripos($enum->label(), $search) !== false)
                            ->map(fn ($enum) => $enum->value)
                            ->toArray();
                        if (!empty($matchedValues)) {
                            return $query->whereIn('payment_status', $matchedValues);
                        }
                        return $query;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Placed')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search) {
                        if ($date = strtotime($search)) {
                            return $query->whereDate('created_at', date('Y-m-d', $date));
                        }
                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Manage')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (Order $record): string => OrderResource::getUrl('edit', ['record' => $record])),
            ])
            ->defaultPaginationPageOption(10)
            ->paginated([10, 25, 50]);
    }
}
