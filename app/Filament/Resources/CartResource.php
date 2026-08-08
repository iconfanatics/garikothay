<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CartResource\Pages;
use App\Models\Cart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CartResource extends Resource
{
    protected static ?string $model = Cart::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return 'Abandoned Cart';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Abandoned Carts';
    }

    public static function getNavigationLabel(): string
    {
        return 'Abandoned Carts';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('items')
            ->where('updated_at', '<=', now()->subHours(2))
            ->with(['user', 'items']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->paginationPageOptions([50, 100, 250, 'all'])
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->default('Guest')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.phone')
                    ->label('Phone')
                    ->default('N/A')
                    ->searchable(),
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
            ->defaultSort('updated_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Abandoned Cart Details')
                    ->modalContent(fn (Cart $record) => view('filament.widgets.cart-details-modal', ['cart' => $record])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No abandoned carts found')
            ->emptyStateDescription('Great! All carts are either active or converted.');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarts::route('/'),
        ];
    }
}
