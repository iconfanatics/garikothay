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
    protected static ?string $recordTitleAttribute = 'order_number';

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
                    ]),
                    \Filament\Infolists\Components\Grid::make(3)->schema([
                        \Filament\Infolists\Components\TextEntry::make('assignedStaff.name')->label('Assigned Staff')->default('Unassigned'),
                        \Filament\Infolists\Components\TextEntry::make('order_source')->label('Order Source'),
                        \Filament\Infolists\Components\TextEntry::make('customer_type')->label('Customer Type'),
                    ]),
                    \Filament\Infolists\Components\Grid::make(3)->schema([
                        \Filament\Infolists\Components\TextEntry::make('is_fraud')->label('Fraud Flag')->badge()->color(fn ($state) => $state ? 'danger' : 'success')->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                        \Filament\Infolists\Components\TextEntry::make('delivery_method')->label('Delivery Method')->default('N/A'),
                        \Filament\Infolists\Components\TextEntry::make('tracking_number')->label('Tracking Number')->default('N/A'),
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
                            ]),
                            \Filament\Infolists\Components\TextEntry::make('internal_note')
                                ->label('Internal Note')
                                ->hidden(fn ($state) => blank($state))
                                ->columnSpanFull(),
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
                        ->disableOptionWhen(function (string $value, ?\App\Models\Order $record): bool {
                            if (! $record) return false;
                            $currentStatus = $record->status;
                            if ($value === $currentStatus->value) return false;
                            $allowed = array_map(fn($s) => $s->value, $currentStatus->allowedTransitions());
                            return ! in_array($value, $allowed);
                        })
                        ->required(),
                    Forms\Components\Select::make('payment_status')
                        ->label('Payment Status')
                        ->options(\App\Enums\PaymentStatus::options())
                        ->disableOptionWhen(function (string $value, ?\App\Models\Order $record): bool {
                            if (! $record) return false;
                            $currentStatus = $record->payment_status;
                            if ($value === $currentStatus->value) return false;
                            $allowed = array_map(fn($s) => $s->value, $currentStatus->allowedTransitions());
                            return ! in_array($value, $allowed);
                        })
                        ->required(),
                    Forms\Components\TextInput::make('payment_method')
                        ->label('Payment Method')
                        ->formatStateUsing(fn ($state) => $state instanceof \App\Enums\PaymentMethod ? $state->label() : (is_string($state) ? \App\Enums\PaymentMethod::tryFrom($state)?->label() ?? strtoupper($state) : 'N/A'))
                        ->disabled(),
                    Forms\Components\TextInput::make('order_number')
                        ->label('Order Number')
                        ->disabled(),
                ]),
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Select::make('assigned_staff_id')
                        ->label('Assigned Staff')
                        ->relationship('assignedStaff', 'name')
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('order_source')
                        ->label('Order Source')
                        ->options([
                            'Website' => 'Website',
                            'WhatsApp' => 'WhatsApp',
                            'Call' => 'Call',
                        ])
                        ->default('Website'),
                    Forms\Components\Select::make('customer_type')
                        ->label('Customer Type')
                        ->options([
                            'Retail' => 'Retail',
                        ])
                        ->default('Retail'),
                ]),
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Toggle::make('is_fraud')
                        ->label('Flag as Fraud')
                        ->onColor('danger'),
                    Forms\Components\Select::make('delivery_method')
                        ->label('Delivery Method')
                        ->options([
                            'Pathao' => 'Pathao',
                            'RedX' => 'RedX',
                            'Steadfast' => 'Steadfast',
                            'SA Paribahan' => 'SA Paribahan',
                            'Sundarban' => 'Sundarban',
                            'Own Delivery' => 'Own Delivery',
                        ]),
                    Forms\Components\TextInput::make('tracking_number')
                        ->label('Tracking Number'),
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
                        ]),
                        Forms\Components\Textarea::make('internal_note')
                            ->label('Internal Note (For this item)')
                            ->rows(2)
                            ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date & Time')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedStaff.name')
                    ->label('Assigned Staff')
                    ->sortable()
                    ->searchable()
                    ->default('Unassigned'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('order_source')
                    ->label('Order Source')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_type')
                    ->label('Customer Type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment.paid_at')
                    ->label('Payment Date')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_fraud')
                    ->label('Fraud Flag')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->trueColor('danger')
                    ->falseIcon('heroicon-o-check-circle')
                    ->falseColor('success')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Total Items')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->formatStateUsing(fn ($state) => $state instanceof \App\Enums\PaymentMethod ? $state->label() : (is_string($state) ? \App\Enums\PaymentMethod::tryFrom($state)?->label() ?? strtoupper($state) : 'N/A'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('delivery_method')
                    ->label('Delivery Method')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (OrderStatus $state): string => $state->label())
                    ->color(fn (OrderStatus $state): string => $state->color()),
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
                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('Tracking #')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('invoice.invoice_number')
                    ->label('Invoice #')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('shipping_phone')
                    ->label('Shipping Phone')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('shipping_address->phone', 'like', "%{$search}%")
                                     ->orWhereHas('user', fn($q) => $q->where('phone', 'like', "%{$search}%"));
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Order Status')
                    ->options(OrderStatus::options()),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options(PaymentStatus::options()),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'cod' => 'Cash on Delivery',
                        'sslcommerz' => 'SSLCommerz',
                        'stripe' => 'Stripe',
                        'bkash' => 'bKash',
                    ]),
                Tables\Filters\SelectFilter::make('delivery_method')
                    ->label('Delivery Method')
                    ->options([
                        'Pathao' => 'Pathao',
                        'RedX' => 'RedX',
                        'Steadfast' => 'Steadfast',
                        'SA Paribahan' => 'SA Paribahan',
                        'Sundarban' => 'Sundarban',
                        'Own Delivery' => 'Own Delivery',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('From Date'),
                        Forms\Components\DatePicker::make('until')->label('To Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
                Tables\Filters\Filter::make('total')
                    ->form([
                        Forms\Components\TextInput::make('min')->label('Min Amount')->numeric(),
                        Forms\Components\TextInput::make('max')->label('Max Amount')->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['min'], fn ($q, $min) => $q->where('total', '>=', $min))
                            ->when($data['max'], fn ($q, $max) => $q->where('total', '<=', $max));
                    }),
                Tables\Filters\SelectFilter::make('assigned_staff_id')
                    ->label('Assigned Staff')
                    ->relationship('assignedStaff', 'name'),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContent)
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
                Tables\Actions\Action::make('download_vendor_slip')
                    ->label('Vendor Slip')
                    ->icon('heroicon-o-truck')
                    ->color('warning')
                    ->action(function (Order $record) {
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.vendor-slip', ['order' => $record]);
                        return response()->streamDownload(fn () => print($pdf->output()), 'vendor-slip-' . $record->order_number . '.pdf');
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
