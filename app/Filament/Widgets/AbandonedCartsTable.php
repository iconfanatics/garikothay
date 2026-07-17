<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Cart;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class AbandonedCartsTable extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Cart::query()
                    ->whereHas('items')
                    ->where('updated_at', '<=', now()->subHours(2))
                    ->with(['user', 'items'])
                    ->latest('updated_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->default('Guest')
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_count')
                    ->label('Items')
                    ->getStateUsing(fn (Cart $record): int => $record->item_count),
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Cart Value')
                    ->money('BDT')
                    ->getStateUsing(fn (Cart $record): float => $record->subtotal),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Active')
                    ->dateTime()
                    ->sortable(),
            ])
            ->heading('Recent Abandoned Carts')
            ->emptyStateHeading('No abandoned carts found')
            ->emptyStateDescription('Great! All carts are either active or converted.')
            ->actions([
                Tables\Actions\Action::make('view_details')
                    ->label('View Details')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Abandoned Cart Details')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalContent(fn (Cart $record) => view('filament.widgets.cart-details-modal', ['cart' => $record])),
            ])
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5);
    }
}
