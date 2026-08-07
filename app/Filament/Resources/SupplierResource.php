<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Catalog';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_person')
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_number')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\Textarea::make('address')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active Supplier')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->paginationPageOptions([50, 100, 250, 'all'])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Products')
                    ->sortable()
                    ->url(fn (\App\Models\Supplier $record): string => \App\Filament\Resources\ProductResource::getUrl('index', ['tableFilters' => ['supplier_id' => ['value' => $record->id]]])),
                Tables\Columns\TextColumn::make('order_items_count')
                    ->counts('orderItems')
                    ->label('Times Ordered')
                    ->sortable()
                    ->url(fn (\App\Models\Supplier $record): string => \App\Filament\Resources\OrderResource::getUrl('index', ['tableFilters' => ['supplier_id' => ['value' => $record->id]]])),
                Tables\Columns\TextColumn::make('contact_person')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_number')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('mark_products_out_of_stock')
                        ->label('Mark Products Out of Stock')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            \App\Models\Product::whereIn('supplier_id', $records->pluck('id'))
                                ->update([
                                    'stock_quantity' => 0,
                                    'supplier_stock_status' => 'out_of_stock'
                                ]);
                            \Filament\Notifications\Notification::make()
                                ->title('Products updated')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('update_products_stock')
                        ->label('Update Products Stock')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->form([
                            Forms\Components\TextInput::make('stock_quantity')
                                ->label('Stock Quantity')
                                ->numeric()
                                ->required()
                                ->default(100),
                        ])
                        ->action(function ($records, array $data) {
                            \App\Models\Product::whereIn('supplier_id', $records->pluck('id'))
                                ->update([
                                    'stock_quantity' => $data['stock_quantity'],
                                    'supplier_stock_status' => 'in_stock'
                                ]);
                            \Filament\Notifications\Notification::make()
                                ->title('Products updated')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSuppliers::route('/'),
        ];
    }
}
