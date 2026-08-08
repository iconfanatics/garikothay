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
                        \Filament\Infolists\Components\TextEntry::make('steadfast_tracking_code')
                            ->label('Steadfast Tracking Code')
                            ->hidden(fn ($record) => blank($record->steadfast_tracking_code))
                            ->copyable(),
                        \Filament\Infolists\Components\TextEntry::make('steadfast_status')
                            ->label('Steadfast Status')
                            ->hidden(fn ($record) => blank($record->steadfast_status))
                            ->badge(),
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
        $isWebsiteOrder = fn (?Order $record) => $record !== null && $record->order_source === 'Website';

        $updateParentTotals = function (Forms\Get $get, Forms\Set $set) {
            $items = $get('../../items') ?? [];
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += (float) ($item['total_price'] ?? 0);
            }
            $set('../../subtotal', $subtotal);

            $discount = (float) $get('../../discount_amount');
            $shipping = (float) $get('../../shipping_amount');

            $couponId = $get('../../coupon_id');
            if ($couponId) {
                $coupon = \App\Models\Coupon::find($couponId);
                if ($coupon) {
                    if ($coupon->type === \App\Enums\CouponType::Percentage || $coupon->type->value === 'percentage') {
                        $discount = $subtotal * ((float) $coupon->value / 100);
                        if ($coupon->max_discount_amount) {
                            $discount = min($discount, (float) $coupon->max_discount_amount);
                        }
                    } else {
                        $discount = (float) $coupon->value;
                    }
                    $set('../../discount_amount', $discount);
                }
            }

            $set('../../total', max(0, $subtotal - $discount + $shipping));
        };

        $updateTotals = function (Forms\Get $get, Forms\Set $set) {
            $items = $get('items') ?? [];
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += (float) ($item['total_price'] ?? 0);
            }
            $set('subtotal', $subtotal);

            $discount = (float) $get('discount_amount');
            $shipping = (float) $get('shipping_amount');

            $couponId = $get('coupon_id');
            if ($couponId) {
                $coupon = \App\Models\Coupon::find($couponId);
                if ($coupon) {
                    if ($coupon->type === \App\Enums\CouponType::Percentage || $coupon->type->value === 'percentage') {
                        $discount = $subtotal * ((float) $coupon->value / 100);
                        if ($coupon->max_discount_amount) {
                            $discount = min($discount, (float) $coupon->max_discount_amount);
                        }
                    } else {
                        $discount = (float) $coupon->value;
                    }
                    $set('discount_amount', $discount);
                }
            }

            $set('total', max(0, $subtotal - $discount + $shipping));
        };

        $locations = [
            'Dhaka' => ['Dhaka', 'Faridpur', 'Gazipur', 'Gopalganj', 'Kishoreganj', 'Madaripur', 'Manikganj', 'Munshiganj', 'Narayanganj', 'Narsingdi', 'Rajbari', 'Shariatpur', 'Tangail'],
            'Chattogram' => ['Bandarban', 'Brahmanbaria', 'Chandpur', 'Chattogram', 'Comilla', 'Cox\'s Bazar', 'Feni', 'Khagrachhari', 'Lakshmipur', 'Noakhali', 'Rangamati'],
            'Rajshahi' => ['Bogra', 'Chapainawabganj', 'Joypurhat', 'Naogaon', 'Natore', 'Pabna', 'Rajshahi', 'Sirajganj'],
            'Khulna' => ['Bagerhat', 'Chuadanga', 'Jessore', 'Jhenaidah', 'Khulna', 'Kushtia', 'Magura', 'Meherpur', 'Narail', 'Satkhira'],
            'Barishal' => ['Barguna', 'Barishal', 'Bhola', 'Jhalokati', 'Patuakhali', 'Pirojpur'],
            'Sylhet' => ['Habiganj', 'Moulvibazar', 'Sunamganj', 'Sylhet'],
            'Rangpur' => ['Dinajpur', 'Gaibandha', 'Kurigram', 'Lalmonirhat', 'Nilphamari', 'Panchagarh', 'Rangpur', 'Thakurgaon'],
            'Mymensingh' => ['Jamalpur', 'Mymensingh', 'Netrokona', 'Sherpur']
        ];

        return $form->schema([
            Forms\Components\Section::make('Manage Order')->schema([
                Forms\Components\Grid::make(4)->schema([
                    Forms\Components\Select::make('status')
                        ->label('Order Status')
                        ->options(\App\Enums\OrderStatus::class)
                        ->default(\App\Enums\OrderStatus::Pending->value)
                        ->required(),
                    Forms\Components\Select::make('payment_status')
                        ->label('Payment Status')
                        ->options(\App\Enums\PaymentStatus::class)
                        ->default(\App\Enums\PaymentStatus::Unpaid->value)
                        ->required(),
                    Forms\Components\Select::make('payment_method')
                        ->label('Payment Method')
                        ->options([
                            'cod' => 'Cash on Delivery',
                            'sslcommerz' => 'SSLCommerz',
                            'stripe' => 'Stripe',
                            'bkash' => 'bKash',
                        ])
                        ->default('cod')
                        ->disabled($isWebsiteOrder)
                        ->required(),
                    Forms\Components\TextInput::make('order_number')
                        ->label('Order Number')
                        ->default(fn () => 'GNG-' . date('Ymd') . '-' . mt_rand(1000, 9999))
                        ->required()
                        ->disabled($isWebsiteOrder)
                        ->unique(Order::class, 'order_number', ignoreRecord: true),
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
                        ->disabled($isWebsiteOrder)
                        ->default('Call'),
                    Forms\Components\Select::make('customer_type')
                        ->label('Customer Type')
                        ->options([
                            'Retail' => 'Retail',
                            'Wholesale' => 'Wholesale',
                        ])
                        ->disabled($isWebsiteOrder)
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
                        ])
                        ->default('Steadfast'),
                    Forms\Components\TextInput::make('tracking_number')
                        ->label('Tracking Number (Optional)')
                        ->default(fn () => 'TRK-' . date('Ymd') . '-' . mt_rand(100, 999)),
                ]),
                Forms\Components\Textarea::make('notes')
                    ->label('Admin Notes')
                    ->rows(2),
            ]),
            Forms\Components\Section::make('Customer & Shipping Info')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Select::make('user_id')
                        ->label('Registered Customer (Optional)')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->disabled($isWebsiteOrder),
                    Forms\Components\TextInput::make('shipping_address.full_name')
                        ->label('Customer Name')
                        ->required()
                        ->disabled($isWebsiteOrder),
                    Forms\Components\TextInput::make('shipping_address.phone')
                        ->label('Phone Number')
                        ->required()
                        ->disabled($isWebsiteOrder),
                    Forms\Components\TextInput::make('shipping_address.address_line_1')
                        ->label('Full Address')
                        ->required()
                        ->disabled($isWebsiteOrder),
                ]),
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Select::make('shipping_address.division')
                        ->label('Division')
                        ->options(array_combine(array_keys($locations), array_keys($locations)))
                        ->live()
                        ->disabled($isWebsiteOrder),
                    Forms\Components\Select::make('shipping_address.city')
                        ->label('City (District)')
                        ->options(fn (Forms\Get $get): array => 
                            $get('shipping_address.division') ? array_combine($locations[$get('shipping_address.division')] ?? [], $locations[$get('shipping_address.division')] ?? []) : []
                        )
                        ->disabled($isWebsiteOrder),
                    Forms\Components\TextInput::make('shipping_address.upazila')
                        ->label('Upazila/Area')
                        ->disabled($isWebsiteOrder),
                ]),
            ]),
            Forms\Components\Section::make('Order Items')->schema([
                Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->disabled($isWebsiteOrder)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) use ($updateTotals) {
                        $updateTotals($get, $set);
                    })
                    ->schema([
                        Forms\Components\Grid::make(5)->schema([
                            Forms\Components\Select::make('product_id')
                                ->label('Product')
                                ->searchable()
                                ->getSearchResultsUsing(fn (string $search): array => \App\Models\Product::where('sku', 'like', "%{$search}%")->orWhereHas('translations', fn ($q) => $q->where('name', 'like', "%{$search}%"))->limit(50)->get()->mapWithKeys(fn ($p) => [$p->id => $p->name . ($p->sku ? ' (SKU: ' . $p->sku . ')' : '')])->toArray())
                                ->getOptionLabelUsing(fn ($value): ?string => ($p = \App\Models\Product::find($value)) ? $p->name . ($p->sku ? ' (SKU: ' . $p->sku . ')' : '') : null)
                                ->preload()
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) use ($updateParentTotals) {
                                    if ($state) {
                                        $product = \App\Models\Product::find($state);
                                        if ($product) {
                                            $set('product_name', $product->name);
                                            $set('product_sku', $product->sku);
                                            $set('unit_price', $product->selling_price);
                                            $set('total_price', $product->selling_price * (float) $get('quantity'));
                                            $updateParentTotals($get, $set);
                                        }
                                    }
                                }),
                            Forms\Components\Hidden::make('product_name'),
                            Forms\Components\Hidden::make('product_sku'),
                            Forms\Components\Select::make('variant_id')
                                ->label('Variant (Optional)')
                                ->options(function (Forms\Get $get) {
                                    $productId = $get('product_id');
                                    if (! $productId) return [];
                                    return \App\Models\ProductVariant::where('product_id', $productId)->get()->mapWithKeys(fn ($v) => [$v->id => (string) ($v->name ?? 'Variant #'.$v->id)]);
                                })
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) use ($updateParentTotals) {
                                    if ($state) {
                                        $variant = \App\Models\ProductVariant::find($state);
                                        $product = \App\Models\Product::find($get('product_id'));
                                        if ($variant && $product) {
                                            $price = $variant->price > 0 ? $variant->price : $product->selling_price + $variant->price_modifier;
                                            $set('unit_price', $price);
                                            $set('total_price', $price * (float) $get('quantity'));
                                            $updateParentTotals($get, $set);
                                        }
                                    }
                                }),
                            Forms\Components\TextInput::make('quantity')
                                ->label('Qty')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) use ($updateParentTotals) {
                                    $set('total_price', (float) $state * (float) $get('unit_price'));
                                    $updateParentTotals($get, $set);
                                }),
                            Forms\Components\TextInput::make('unit_price')
                                ->label('Unit Price')
                                ->numeric()
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) use ($updateParentTotals) {
                                    $set('total_price', (float) $state * (float) $get('quantity'));
                                    $updateParentTotals($get, $set);
                                }),
                            Forms\Components\TextInput::make('total_price')
                                ->label('Total')
                                ->numeric()
                                ->required()
                                ->readOnly(),
                        ]),
                        Forms\Components\Textarea::make('internal_note')
                            ->label('Internal Note (For this item)')
                            ->rows(1)
                            ->columnSpanFull(),
                    ])
                    ->defaultItems(1)
                    ->columns(1)
            ]),
            Forms\Components\Section::make('Financials')->schema([
                Forms\Components\Grid::make(4)->schema([
                    Forms\Components\TextInput::make('subtotal')->label('Subtotal')->numeric()->required()->disabled($isWebsiteOrder),
                    Forms\Components\TextInput::make('discount_amount')->label('Discount')->numeric()->default(0)->required()
                        ->disabled($isWebsiteOrder)
                        ->live(onBlur: true)->afterStateUpdated(function(Forms\Get $get, Forms\Set $set, $state) use ($updateTotals) { $updateTotals($get, $set); }),
                    Forms\Components\TextInput::make('shipping_amount')->label('Shipping')->numeric()->default(0)->required()
                        ->disabled($isWebsiteOrder)
                        ->live(onBlur: true)->afterStateUpdated(function(Forms\Get $get, Forms\Set $set, $state) use ($updateTotals) { $updateTotals($get, $set); }),
                    Forms\Components\TextInput::make('total')->label('Total')->numeric()->required()->disabled($isWebsiteOrder),
                ]),
                Forms\Components\Select::make('coupon_id')
                    ->label('Coupon (Optional)')
                    ->relationship('coupon', 'code')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->disabled($isWebsiteOrder)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function(Forms\Get $get, Forms\Set $set, $state) use ($updateTotals) { $updateTotals($get, $set); }),
            ]),
            Forms\Components\Section::make('Payment Info (Optional)')->schema([
                Forms\Components\TextInput::make('manual_payment_amount')
                    ->label('Amount Paid')
                    ->numeric()
                    ->placeholder('e.g. 500'),
                Forms\Components\TextInput::make('manual_payment_reference')
                    ->label('Transaction ID / Reference')
                    ->placeholder('e.g. TrxID...'),
            ])->hiddenOn('edit'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordClasses(fn (Order $record) => $record->status === OrderStatus::Pending ? 'bg-primary-50/50 dark:bg-primary-900/10 border-l-4 border-primary-500 font-semibold' : null)
            ->defaultPaginationPageOption(50)
            ->paginationPageOptions([50, 100, 250, 'all'])
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
                    
                Tables\Filters\Filter::make('payment')
                    ->label('Payment Details')
                    ->form([
                        Forms\Components\Select::make('payment_status')
                            ->label('Payment Status')
                            ->options(PaymentStatus::options())
                            ->live(),
                        Forms\Components\Select::make('payment_method')
                            ->label('Payment Method')
                            ->options(function (Forms\Get $get) {
                                if ($get('payment_status') === 'unpaid') {
                                    return ['cod' => 'Cash on Delivery'];
                                }
                                return [
                                    'cod' => 'Cash on Delivery',
                                    'sslcommerz' => 'SSLCommerz',
                                    'stripe' => 'Stripe',
                                    'bkash' => 'bKash',
                                ];
                            }),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['payment_status'] ?? null, fn($q, $v) => $q->where('payment_status', $v))
                            ->when($data['payment_method'] ?? null, fn($q, $v) => $q->where('payment_method', $v));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['payment_status'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Payment Status: ' . str($data['payment_status'])->headline())
                                ->removeField('payment_status');
                        }
                        if ($data['payment_method'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Payment Method: ' . str($data['payment_method'])->headline())
                                ->removeField('payment_method');
                        }
                        return $indicators;
                    }),

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
                    ->label('Date Range')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From Date')
                            ->maxDate(now())
                            ->live(),
                        Forms\Components\DatePicker::make('until')
                            ->label('To Date')
                            ->maxDate(now())
                            ->minDate(fn (Forms\Get $get) => $get('from')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('From: ' . \Carbon\Carbon::parse($data['from'])->format('d M, Y'))
                                ->removeField('from');
                        }
                        if ($data['until'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('To: ' . \Carbon\Carbon::parse($data['until'])->format('d M, Y'))
                                ->removeField('until');
                        }
                        return $indicators;
                    }),
                    
                Tables\Filters\Filter::make('total')
                    ->label('Order Amount')
                    ->form([
                        Forms\Components\TextInput::make('min')
                            ->label('Min Amount')
                            ->numeric()
                            ->live(onBlur: true),
                        Forms\Components\TextInput::make('max')
                            ->label('Max Amount')
                            ->numeric()
                            ->rule(fn (Forms\Get $get) => function (string $attribute, $value, \Closure $fail) use ($get) {
                                if (filled($get('min')) && (float) $value < (float) $get('min')) {
                                    $fail('Max amount cannot be less than Min amount.');
                                }
                            }),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['min'], fn ($q, $min) => $q->where('total', '>=', $min))
                            ->when($data['max'], fn ($q, $max) => $q->where('total', '<=', $max));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['min'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Min Amount: ৳' . $data['min'])
                                ->removeField('min');
                        }
                        if ($data['max'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Max Amount: ৳' . $data['max'])
                                ->removeField('max');
                        }
                        return $indicators;
                    }),
                    
                Tables\Filters\SelectFilter::make('assigned_staff_id')
                    ->label('Assigned Staff')
                    ->relationship('assignedStaff', 'name'),
                    
                Tables\Filters\SelectFilter::make('supplier_id')
                    ->label('Supplier')
                    ->options(fn () => \App\Models\Supplier::pluck('name', 'id')->toArray())
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) return $query;
                        return $query->whereHas('items.product', fn($q) => $q->where('supplier_id', $data['value']));
                    }),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::Modal)
            ->filtersFormColumns(2)
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
                Tables\Actions\Action::make('send_to_steadfast')
                    ->label('Send to Steadfast')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->hidden(fn (Order $record) => $record->steadfast_consignment_id !== null)
                    ->action(function (Order $record) {
                        try {
                            $service = new \App\Services\SteadfastCourierService();
                            $result = $service->createOrder($record);
                            
                            $record->update([
                                'steadfast_consignment_id' => $result['consignment_id'],
                                'steadfast_tracking_code' => $result['tracking_code'],
                                'tracking_number' => $result['tracking_code'],
                                'steadfast_status' => $result['status'],
                                'delivery_method' => 'Steadfast',
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Sent to Steadfast Courier')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Failed to send to Steadfast')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('check_steadfast_status')
                    ->label('Check Delivery Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('secondary')
                    ->hidden(fn (Order $record) => $record->steadfast_consignment_id === null)
                    ->action(function (Order $record) {
                        try {
                            $service = new \App\Services\SteadfastCourierService();
                            $result = $service->checkDeliveryStatus((string)$record->steadfast_consignment_id);
                            
                            if ($result['delivery_status'] !== 'error' && $result['delivery_status'] !== 'unknown') {
                                $record->update(['steadfast_status' => $result['delivery_status']]);
                                \Filament\Notifications\Notification::make()
                                    ->title('Status updated: ' . ucfirst($result['delivery_status']))
                                    ->success()
                                    ->send();
                            } else {
                                \Filament\Notifications\Notification::make()
                                    ->title('Could not fetch status')
                                    ->body($result['message'] ?? 'Unknown error')
                                    ->warning()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Error checking status')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
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
                    Tables\Actions\BulkAction::make('bulk_download_vendor_slip')
                        ->label('Download Vendor Slip (Consolidated)')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('warning')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.bulk-vendor-slip', ['orders' => $records]);
                            return response()->streamDownload(fn () => print($pdf->output()), 'bulk-vendor-slip-' . now()->format('YmdHi') . '.pdf');
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('bulk_send_to_steadfast')
                        ->label('Send to Steadfast Courier')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('info')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $service = new \App\Services\SteadfastCourierService();
                            $successCount = 0;
                            $failCount = 0;
                            
                            foreach ($records as $record) {
                                if ($record->steadfast_consignment_id) {
                                    continue;
                                }
                                
                                try {
                                    $result = $service->createOrder($record);
                                    $record->update([
                                        'steadfast_consignment_id' => $result['consignment_id'],
                                        'steadfast_tracking_code' => $result['tracking_code'],
                                        'tracking_number' => $result['tracking_code'],
                                        'steadfast_status' => $result['status'],
                                        'delivery_method' => 'Steadfast',
                                    ]);
                                    $successCount++;
                                } catch (\Exception $e) {
                                    $failCount++;
                                }
                            }
                            
                            if ($successCount > 0) {
                                \Filament\Notifications\Notification::make()
                                    ->title("{$successCount} orders sent to Steadfast")
                                    ->success()
                                    ->send();
                            }
                            if ($failCount > 0) {
                                \Filament\Notifications\Notification::make()
                                    ->title("{$failCount} orders failed to send to Steadfast")
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
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
