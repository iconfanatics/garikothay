<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\ProductVariant;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Info')->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\Select::make('variant_type_id')
                            ->relationship('variantType', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Variant Type')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')->required()
                            ])
                            ->required(),
                        Forms\Components\Select::make('variant_value_id')
                            ->relationship('variantValue', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Variant Value')
                            ->createOptionForm([
                                Forms\Components\Select::make('variant_type_id')
                                    ->relationship('type', 'name')
                                    ->required(),
                                Forms\Components\TextInput::make('name')->required()
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU (Optional)')
                            ->readOnly(fn (string $operation): bool => $operation === 'edit')
                            ->unique(ignoreRecord: true)
                            ->nullable()
                            ->default(fn () => 'VAR-' . strtoupper(str()->random(6))),
                    ]),
                ]),

                Forms\Components\Section::make('Pricing & Profit')->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('cost_price')
                            ->label('Supplier Price (৳)')
                            ->helperText('Internal purchase price for variant.')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('৳')
                            ->live(onBlur: true)
                            ->disabled(fn () => auth()->user()?->hasRole('Shop Manager')),
                        Forms\Components\TextInput::make('price')
                            ->label('Selling Price (৳)')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('৳')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                if ($state === null || $state === '') return;
                                $cost = (float) $get('cost_price');
                                $val = (float) $state;
                                if ($cost > 0 && $val < $cost) {
                                    $set('price', $cost);
                                    \Filament\Notifications\Notification::make()->warning()->title('Selling price automatically adjusted to match Supplier Price.')->send();
                                }
                            })
                            ->rule(function (Forms\Get $get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $cost = (float) $get('cost_price');
                                    if ($cost > 0 && (float) $value < $cost) {
                                        $fail('Selling price cannot be less than Supplier Price.');
                                    }
                                };
                            })
                            ->disabled(fn () => auth()->user()?->hasRole('Shop Manager'))
                            ->helperText('Overrides base price if set.'),
                        Forms\Components\Placeholder::make('profit_margin_preview')
                            ->label('Profit Margin')
                            ->content(function (Forms\Get $get): string {
                                $supplierPrice = (float) ($get('cost_price') ?? 0);
                                $sellingPrice = (float) ($get('price') ?? 0);
                                $discountType = $get('discount_type');
                                $discountAmount = (float) ($get('discount_amount') ?? 0);

                                if ($sellingPrice <= 0 || $get('cost_price') === null || $get('cost_price') === '') {
                                    return 'Enter prices';
                                }

                                if ($discountAmount > 0 && $discountType) {
                                    $discount = $discountType === 'Percentage' ? ($sellingPrice * ($discountAmount / 100)) : $discountAmount;
                                    $sellingPrice = max(0, $sellingPrice - $discount);
                                }

                                $profit = $sellingPrice - $supplierPrice;
                                $percentage = $sellingPrice > 0 ? ($profit / $sellingPrice) * 100 : 0;

                                return '৳' . number_format($profit, 2) . ' (' . number_format($percentage, 2) . '%)';
                            }),
                    ]),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('compare_price')
                            ->label('Compare at Price (৳)')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('৳')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                if ($state === null || $state === '') return;
                                $cost = (float) $get('cost_price');
                                $val = (float) $state;
                                if ($cost > 0 && $val < $cost) {
                                    $set('compare_price', $cost);
                                    \Filament\Notifications\Notification::make()->warning()->title('Compare at Price automatically adjusted to match Supplier Price.')->send();
                                }
                            })
                            ->rule(function (Forms\Get $get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $cost = (float) $get('cost_price');
                                    if ($cost > 0 && (float) $value < $cost) {
                                        $fail('Compare at Price cannot be less than Supplier Price.');
                                    }
                                };
                            })
                            ->disabled(fn () => auth()->user()?->hasRole('Shop Manager')),
                        Forms\Components\TextInput::make('price_modifier')
                            ->label('Price Modifier (৳)')
                            ->numeric()
                            ->prefix('৳')
                            ->helperText('Amount added to base price if no specific selling price is set.')
                            ->disabled(fn () => auth()->user()?->hasRole('Shop Manager')),
                    ]),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('discount_type')
                            ->label('Discount Type')
                            ->options([
                                'Fixed' => 'Fixed Amount',
                                'Percentage' => 'Percentage (%)',
                            ])
                            ->live()
                            ->disabled(fn () => auth()->user()?->hasRole('Shop Manager')),
                        Forms\Components\TextInput::make('discount_amount')
                            ->label('Discount Amount')
                            ->numeric()
                            ->prefix(fn (Forms\Get $get) => $get('discount_type') === 'Percentage' ? null : '৳')
                            ->suffix(fn (Forms\Get $get) => $get('discount_type') === 'Percentage' ? '%' : null)
                            ->live(onBlur: true)
                            ->disabled(fn () => auth()->user()?->hasRole('Shop Manager'))
                            ->rule(function (Forms\Get $get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    if ($get('discount_type') === 'Percentage' && $value >= 100) {
                                        $fail('Percentage discount must be less than 100.');
                                    }
                                    if ($get('discount_type') === 'Fixed' && (float) $value >= (float) $get('price')) {
                                        $fail('Fixed discount must be less than the selling price.');
                                    }
                                    $cost = (float) $get('cost_price');
                                    $price = (float) $get('price');
                                    $discount = $get('discount_type') === 'Percentage' ? ($price * ($value / 100)) : (float) $value;
                                    $finalPrice = $price - $discount;
                                    if ($cost > 0 && $finalPrice < $cost) {
                                        $fail('Final price after discount cannot be less than Supplier Price.');
                                    }
                                };
                            }),
                        Forms\Components\Placeholder::make('final_price_preview')
                            ->label('Final Price Preview')
                            ->content(function (Forms\Get $get) {
                                $price = (float) $get('price');
                                $discountType = $get('discount_type');
                                $discountAmount = (float) $get('discount_amount');
                                if (! $price || ! $discountAmount || ! $discountType) return '-';
                                $final = $discountType === 'Percentage' ? $price - ($price * ($discountAmount / 100)) : $price - $discountAmount;
                                return '৳' . number_format(max(0, $final), 2);
                            }),
                        Forms\Components\DateTimePicker::make('discount_start_date')
                            ->label('Discount Start Date')
                            ->nullable()
                            ->minDate(now())
                            ->rule(function (Forms\Get $get, ?ProductVariant $record) {
                                return function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                                    $valueDate = \Carbon\Carbon::parse($value);
                                    if (!$record || $record->discount_start_date?->format('Y-m-d H:i') !== $valueDate->format('Y-m-d H:i')) {
                                        if ($valueDate->isPast()) {
                                            $fail('Discount Start Date cannot be in the past.');
                                        }
                                    }
                                };
                            }),
                        Forms\Components\DateTimePicker::make('discount_end_date')
                            ->label('Discount End Date')
                            ->nullable()
                            ->minDate(now())
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                $start = $get('discount_start_date');
                                if ($start && $state && \Carbon\Carbon::parse($state)->isBefore(\Carbon\Carbon::parse($start))) {
                                    $set('discount_end_date', null);
                                    \Filament\Notifications\Notification::make()->warning()->title('Discount End Date cannot be before Start Date.')->send();
                                }
                            })
                            ->afterOrEqual('discount_start_date')
                            ->rule(function (Forms\Get $get, ?ProductVariant $record) {
                                return function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                                    $valueDate = \Carbon\Carbon::parse($value);
                                    if (!$record || $record->discount_end_date?->format('Y-m-d H:i') !== $valueDate->format('Y-m-d H:i')) {
                                        if ($valueDate->isPast()) {
                                            $fail('Discount End Date cannot be in the past.');
                                        }
                                    }
                                };
                            }),
                    ]),
                ]),

                Forms\Components\Section::make('Inventory & Status')->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('stock_quantity')
                            ->label('Stock')
                            ->numeric()
                            ->required()
                            ->default(0),
                        Forms\Components\TextInput::make('low_stock_threshold')
                            ->label('Reorder Level')
                            ->numeric()
                            ->default(5),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active Status')
                            ->default(true),
                    ]),
                ]),

                Forms\Components\Section::make('Shipping Dimensions')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Select::make('weight_unit')
                                ->label('Weight Unit')
                                ->options([
                                    'g' => 'Grams (g)',
                                    'kg' => 'Kilograms (kg)',
                                    'lb' => 'Pounds (lb)',
                                    'oz' => 'Ounces (oz)'
                                ])
                                ->default('g'),
                            Forms\Components\TextInput::make('weight_value')
                                ->label('Weight Value')
                                ->numeric(),
                        ])->columnSpan(1),
                        Forms\Components\Grid::make(4)->schema([
                            Forms\Components\Select::make('dimension_unit')
                                ->label('Unit')
                                ->options([
                                    'cm' => 'cm',
                                    'in' => 'in',
                                    'm' => 'm'
                                ])
                                ->default('cm'),
                            Forms\Components\TextInput::make('length')->label('L')->numeric(),
                            Forms\Components\TextInput::make('width')->label('W')->numeric(),
                            Forms\Components\TextInput::make('height')->label('H')->numeric(),
                        ])->columnSpan(1),
                    ]),
                ]),

                Forms\Components\Section::make('Media')->schema([
                    Forms\Components\FileUpload::make('image_gallery')
                        ->label('Variant Specific Images')
                        ->helperText('These images will be displayed when this variant is selected.')
                        ->multiple()
                        ->image()
                        ->directory('products/variants')
                        ->reorderable()
                        ->columnSpanFull(),
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('image_gallery')
                    ->label('Image')
                    ->circular()
                    ->limit(1)
                    ->getStateUsing(fn ($record) => is_array($record->image_gallery) && count($record->image_gallery) > 0 ? $record->image_gallery[0] : null),
                Tables\Columns\TextColumn::make('variantType.name')
                    ->label('Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('variantValue.name')
                    ->label('Value')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('bdt')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($state, $record) => $state <= $record->low_stock_threshold ? 'danger' : 'success'),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
