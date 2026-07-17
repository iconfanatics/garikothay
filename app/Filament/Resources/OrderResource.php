<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', OrderStatus::Pending)->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

        public static function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make('Order Information')->schema([
                    \Filament\Infolists\Components\Grid::make(4)->schema([
                        \Filament\Infolists\Components\TextEntry::make('order_number')->label('Order Number')->weight('bold'),
                        \Filament\Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn (\App\Enums\OrderStatus $state): string => $state->label())
                            ->color(fn (\App\Enums\OrderStatus $state): string => $state->color()),
                        \Filament\Infolists\Components\TextEntry::make('payment_status')
                            ->badge()
                            ->formatStateUsing(fn (\App\Enums\PaymentStatus $state): string => $state->label())
                            ->color(fn (\App\Enums\PaymentStatus $state): string => $state->color()),
                        \Filament\Infolists\Components\TextEntry::make('created_at')
                            ->label('Order Date')
                            ->dateTime('d M Y, h:i A'),
                    ])
                ]),
                \Filament\Infolists\Components\Section::make('Customer & Shipping Details')->schema([
                    \Filament\Infolists\Components\Grid::make(2)->schema([
                        \Filament\Infolists\Components\TextEntry::make('shipping_full_name')->label('Customer Name')->default(fn ($record) => $record->user->name ?? 'N/A'),
                        \Filament\Infolists\Components\TextEntry::make('shipping_phone')->label('Phone Number')->default(fn ($record) => $record->user->phone ?? 'N/A'),
                        \Filament\Infolists\Components\TextEntry::make('user.email')->label('Email Address')->default('N/A'),
                        \Filament\Infolists\Components\TextEntry::make('shipping_full_address')->label('Full Address')->default('N/A'),
                    ])
                ]),
                \Filament\Infolists\Components\Section::make('Order Items')->schema([
                    \Filament\Infolists\Components\RepeatableEntry::make('items')
                        ->schema([
                            \Filament\Infolists\Components\Grid::make(4)->schema([
                                \Filament\Infolists\Components\TextEntry::make('product_name')->label('Product'),
                                \Filament\Infolists\Components\TextEntry::make('variant.name')
                                    ->label('Variant')
                                    ->default('N/A'),
                                \Filament\Infolists\Components\TextEntry::make('quantity')->label('Qty'),
                                \Filament\Infolists\Components\TextEntry::make('total_price')->label('Total')->money('BDT'),
                            ])
                        ])
                        ->columns(1)
                ]),
                \Filament\Infolists\Components\Section::make('Financials')->schema([
                    \Filament\Infolists\Components\Grid::make(5)->schema([
                        \Filament\Infolists\Components\TextEntry::make('subtotal')->money('BDT'),
                        \Filament\Infolists\Components\TextEntry::make('discount_amount')->label('Discount')->money('BDT'),
                        \Filament\Infolists\Components\TextEntry::make('shipping_amount')->label('Shipping')->money('BDT'),
                        \Filament\Infolists\Components\TextEntry::make('total')->money('BDT')->weight('bold'),
                        \Filament\Infolists\Components\TextEntry::make('coupon.code')->label('Coupon Used')->default('None')->badge(),
                    ]),
                ]),
            ]);
    }

        public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Manage Order')->schema([
                Forms\Components\Grid::make(4)->schema([
                    Forms\Components\Select::make('status')
                        ->label('Order Status')
                        ->options(\App\Enums\OrderStatus::options())
                        ->required(),
                    Forms\Components\Select::make('payment_status')
                        ->label('Payment Status')
                        ->options(\App\Enums\PaymentStatus::options())
                        ->required(),
                    Forms\Components\TextInput::make('payment_method')
                        ->label('Payment Method')
                        ->formatStateUsing(fn ($state) => $state instanceof \App\Enums\PaymentMethod ? $state->label() : (is_string($state) ? \App\Enums\PaymentMethod::tryFrom($state)?->label() ?? strtoupper($state) : 'N/A'))
                        ->disabled(),
                    Forms\Components\TextInput::make('order_number')
                        ->label('Order Number')
                        ->disabled(),
                ]),
                Forms\Components\Textarea::make('notes')
                    ->label('Admin Notes')
                    ->rows(2),
            ]),
            Forms\Components\Section::make('Customer & Shipping Info')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Placeholder::make('customer_name')
                        ->label('Customer Name')
                        ->content(fn ($record) => $record?->user?->name ?? 'N/A'),
                    Forms\Components\Placeholder::make('phone')
                        ->label('Phone Number')
                        ->content(fn ($record) => $record?->shipping_phone ?? $record?->user?->phone ?? 'N/A'),
                    Forms\Components\Placeholder::make('email')
                        ->label('Email Address')
                        ->content(fn ($record) => $record?->user?->email ?? 'N/A'),
                    Forms\Components\Placeholder::make('address')
                        ->label('Full Address')
                        ->content(fn ($record) => $record?->shipping_full_address ?? 'N/A'),
                ]),
            ]),
            Forms\Components\Section::make('Order Items')->schema([
                Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->schema([
                        Forms\Components\Grid::make(4)->schema([
                            Forms\Components\TextInput::make('product_name')->label('Product')->disabled(),
                            Forms\Components\Placeholder::make('variant_name')
                                ->label('Variant')
                                ->content(fn ($record) => $record?->variant?->name ?? 'N/A'),
                            Forms\Components\TextInput::make('quantity')->label('Qty')->disabled(),
                            Forms\Components\TextInput::make('total_price')->label('Total')->prefix('৳')->disabled(),
                        ])
                    ])
                    ->disableItemCreation()
                    ->disableItemDeletion()
                    ->disableItemMovement()
                    ->columns(1)
            ]),
            Forms\Components\Section::make('Financials')->schema([
                Forms\Components\Grid::make(4)->schema([
                    Forms\Components\TextInput::make('subtotal')->label('Subtotal')->prefix('৳')->disabled(),
                    Forms\Components\TextInput::make('discount_amount')->label('Discount')->prefix('৳')->disabled(),
                    Forms\Components\TextInput::make('shipping_amount')->label('Shipping')->prefix('৳')->disabled(),
                    Forms\Components\TextInput::make('total')->label('Total')->prefix('৳')->disabled(),
                ]),
                Forms\Components\TextInput::make('coupon.code')
                    ->label('Coupon Used')
                    ->default('None')
                    ->disabled(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordClasses(fn (Order $record) => $record->status === OrderStatus::Pending ? 'bg-primary-50/50 dark:bg-primary-900/10 border-l-4 border-primary-500 font-semibold' : null)
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('BDT')
                    ->sortable(),
                Tables\Columns\TextColumn::make('coupon.code')
                    ->label('Coupon')
                    ->badge()
                    ->default('None')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->formatStateUsing(fn ($state) => $state instanceof \App\Enums\PaymentMethod ? $state->label() : (is_string($state) ? \App\Enums\PaymentMethod::tryFrom($state)?->label() ?? strtoupper($state) : 'N/A'))
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (OrderStatus $state): string => $state->label())
                    ->color(fn (OrderStatus $state): string => $state->color()),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Payment')
                    ->formatStateUsing(fn (PaymentStatus $state): string => $state->label())
                    ->color(fn (PaymentStatus $state): string => $state->color()),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Placed At')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Order Status')
                    ->options(OrderStatus::options()),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options(PaymentStatus::options()),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('From'),
                        Forms\Components\DatePicker::make('until')->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('From ' . $data['from'])->removeField('from');
                        }
                        if ($data['until'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Until ' . $data['until'])->removeField('until');
                        }
                        return $indicators;
                    }),
            ])
                                    ->actions([
                Tables\Actions\EditAction::make()->label('Manage Order'),
                Tables\Actions\Action::make('download_invoice')
                    ->label('Download Invoice')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function (Order $record) {
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', ['order' => $record]);
                        return response()->streamDownload(fn () => print($pdf->output()), 'invoice-' . $record->order_number . '.pdf');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_confirmed')
                        ->label('Mark as Confirmed')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['status' => OrderStatus::Confirmed]))
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user']);
    }
}
