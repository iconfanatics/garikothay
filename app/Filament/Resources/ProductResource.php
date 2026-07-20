<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = 'Catalog';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Product')->tabs([

                Forms\Components\Tabs\Tab::make('General')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('translations.en.name')
                            ->label('Name (English)')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                        Forms\Components\TextInput::make('translations.bn.name')
                            ->label('Name (বাংলা)')
                            ->maxLength(255),
                    ]),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('parent_category_id')
                            ->label('Parent Category')
                            ->options(fn () => Category::whereNull('parent_id')->with('translations')->get()->pluck('name', 'id'))
                            ->live()
                            ->dehydrated(false)
                            ->afterStateUpdated(fn (Forms\Set $set) => $set('category_id', null))
                            ->afterStateHydrated(function (Forms\Components\Select $component, ?Product $record) {
                                if ($record && $record->category) {
                                    $component->state($record->category->parent_id ?? $record->category_id);
                                }
                            }),
                        Forms\Components\Select::make('category_id')
                            ->label('Category / Subcategory')
                            ->options(function (Forms\Get $get) {
                                $parentId = $get('parent_category_id');
                                if (! $parentId) {
                                    return Category::with('translations')->get()->pluck('name', 'id');
                                }
                                return Category::where('parent_id', $parentId)
                                    ->orWhere('id', $parentId)
                                    ->with('translations')
                                    ->get()
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->required(),
                    ]),
                    Forms\Components\TextInput::make('brand')
                        ->label('Brand')
                        ->nullable()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(Product::class, 'slug', ignoreRecord: true),
                    Forms\Components\Textarea::make('translations.en.short_description')
                        ->label('Short Description (EN)')
                        ->rows(2),
                    Forms\Components\Textarea::make('translations.bn.short_description')
                        ->label('Short Description (BN)')
                        ->rows(2),
                    Forms\Components\RichEditor::make('translations.en.description')
                        ->label('Description (EN)')
                        ->columnSpanFull(),
                    Forms\Components\RichEditor::make('translations.bn.description')
                        ->label('Description (BN)')
                        ->columnSpanFull(),
                    Forms\Components\RichEditor::make('translations.en.specifications')
                        ->label('Specifications (EN)')
                        ->columnSpanFull(),
                    Forms\Components\RichEditor::make('translations.bn.specifications')
                        ->label('Specifications (BN)')
                        ->columnSpanFull(),
                    Forms\Components\RichEditor::make('translations.en.shipping_returns')
                        ->label('Shipping & Returns (EN)')
                        ->columnSpanFull(),
                    Forms\Components\RichEditor::make('translations.bn.shipping_returns')
                        ->label('Shipping & Returns (BN)')
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('image_paths')
                        ->label('Product Images')
                        ->helperText('Recommended: 1200 x 1200 px square image. JPG, PNG or WebP only, maximum 2 MB each. The first image is the primary image.')
                        ->image()
                        ->imageEditor()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(2048)
                        ->imageResizeMode('contain')
                        ->imageResizeTargetWidth('1200')
                        ->imageResizeTargetHeight('1200')
                        ->multiple()
                        ->reorderable()
                        ->maxFiles(8)
                        ->disk('public')
                        ->directory('products')
                        ->visibility('public')
                        ->dehydrated(false)
                        ->columnSpanFull(),
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\Toggle::make('is_active')->label('Active')->default(true),
                        Forms\Components\Toggle::make('is_featured')->label('Featured'),
                        Forms\Components\Toggle::make('is_new_arrival')->label('New Arrival'),
                    ]),
                ]),

                Forms\Components\Tabs\Tab::make('Pricing & Stock')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('cost_price')
                            ->label('Supplier Price (৳)')
                            ->helperText('Internal purchase price. Customers cannot see this.')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('৳')
                            ->live(onBlur: true),
                        Forms\Components\TextInput::make('price')
                            ->label('Selling Price (৳)')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->prefix('৳')
                            ->live(onBlur: true),
                        Forms\Components\TextInput::make('compare_price')->label('Discount / Old Price (৳)')->numeric()->prefix('৳')->helperText('Used to show a discount (e.g., if price is 80 and old price is 100, 20% discount).'),
                        Forms\Components\TextInput::make('minimum_selling_price')
                            ->label('Minimum Selling Price (৳)')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('৳'),
                        Forms\Components\Placeholder::make('profit_margin_preview')
                            ->label('Profit Margin')
                            ->content(function (Forms\Get $get): string {
                                $supplierPrice = (float) ($get('cost_price') ?? 0);
                                $sellingPrice = (float) ($get('price') ?? 0);

                                if ($sellingPrice <= 0 || $get('cost_price') === null || $get('cost_price') === '') {
                                    return 'Enter supplier and selling prices';
                                }

                                $profit = $sellingPrice - $supplierPrice;
                                $percentage = ($profit / $sellingPrice) * 100;

                                return '৳' . number_format($profit, 2) . ' (' . number_format($percentage, 2) . '%)';
                            }),
                    ]),
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->helperText('Leave blank to generate automatically, for example GK-BCDf34.')
                            ->placeholder('Auto-generated if blank')
                            ->nullable()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('stock_quantity')->label('Stock Qty')->numeric()->required()->default(0),
                        Forms\Components\TextInput::make('low_stock_threshold')->label('Low Stock Alert')->numeric()->default(5),
                    ]),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('weight_grams')->label('Weight (grams)')->numeric(),
                        Forms\Components\TextInput::make('tax_rate')->label('Tax Rate (%)')->numeric()->default(0),
                    ]),
                ]),

                Forms\Components\Tabs\Tab::make('Supplier & Operations')->schema([
                    Forms\Components\Section::make('Supplier / Shop Information')
                        ->description('Internal supplier details. These fields are only visible in the admin panel.')
                        ->schema([
                            Forms\Components\Grid::make(1)->schema([
                                Forms\Components\Select::make('supplier_id')
                                    ->relationship('supplier', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->label('Supplier')
                                    ->createOptionForm([
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
                                    ]),
                            ]),
                        ]),

                    Forms\Components\Section::make('Stock Information')->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Select::make('supplier_stock_status')
                                ->label('Supplier Stock Status')
                                ->options([
                                    'in_stock' => 'In Stock',
                                    'limited' => 'Limited Stock',
                                    'out_of_stock' => 'Out of Stock',
                                    'pre_order' => 'Pre-order',
                                    'unknown' => 'Unknown',
                                ])
                                ->native(false),
                            Forms\Components\DatePicker::make('supplier_stock_updated_at')
                                ->label('Stock Update Date')
                                ->maxDate(now()),
                        ]),
                    ]),

                    Forms\Components\Section::make('Product Management')->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('product_source_url')
                                ->label('Product Source')
                                ->helperText('Supplier product page link.')
                                ->url()
                                ->maxLength(2048),
                            Forms\Components\TextInput::make('supplier_product_code')
                                ->label('Supplier Product Code')
                                ->maxLength(255),
                            Forms\Components\Toggle::make('has_return_support')
                                ->label('Return / Replacement Support')
                                ->default(false),
                            Forms\Components\Toggle::make('is_authentic_product')
                                ->label('Authentic Product')
                                ->default(false),
                        ]),
                    ]),

                    Forms\Components\Section::make('Logistics')->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\TextInput::make('supplier_shipping_charge')
                                ->label('Shipping Charge (৳)')
                                ->helperText('Supplier-side shipping cost; not shown to customers.')
                                ->numeric()
                                ->minValue(0)
                                ->prefix('৳'),
                            Forms\Components\TextInput::make('supplier_delivery_time')
                                ->label('Delivery Time')
                                ->placeholder('e.g. 2-3 business days')
                                ->maxLength(255),
                            Forms\Components\Select::make('supplier_delivery_partner')
                                ->label('Delivery Partner Preference')
                                ->options([
                                    'Steadfast' => 'Steadfast',
                                    'Pathao Courier' => 'Pathao Courier',
                                    'RedX' => 'RedX',
                                    'eCourier' => 'eCourier',
                                    'Paperfly' => 'Paperfly',
                                    'Sundarban Courier' => 'Sundarban Courier',
                                    'SA Paribahan' => 'SA Paribahan',
                                    'Janani Express' => 'Janani Express',
                                    'Own Delivery' => 'Own Delivery',
                                ])
                                ->searchable(),
                        ]),
                    ]),

                    Forms\Components\Section::make('Customer Shipping & Handling')->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Toggle::make('requires_shipping')
                                ->label('Requires Shipping')
                                ->default(true)
                                ->helperText('Disable for digital products or services.'),
                            Forms\Components\Select::make('shipping_restriction')
                                ->label('Shipping Restriction')
                                ->options([
                                    'home_delivery' => 'Home Delivery Available',
                                    'pickup_only' => 'Pickup Only',
                                    'courier_restricted' => 'Courier Restricted',
                                ])
                                ->default('home_delivery')
                                ->native(false),
                            Forms\Components\Toggle::make('is_free_shipping_eligible')
                                ->label('Free Shipping Eligible')
                                ->default(false),
                        ]),
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Toggle::make('has_special_handling')
                                ->label('Dangerous / Special Handling')
                                ->default(false)
                                ->live(),
                            Forms\Components\Select::make('handling_type')
                                ->label('Handling Type')
                                ->options([
                                    'fragile' => 'Fragile',
                                    'liquid' => 'Liquid',
                                    'battery' => 'Battery',
                                    'hazardous' => 'Hazardous',
                                ])
                                ->native(false)
                                ->visible(fn (Forms\Get $get) => $get('has_special_handling')),
                        ]),
                    ]),

                    Forms\Components\Section::make('Warranty')->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Select::make('warranty_type')
                                ->label('Warranty Type')
                                ->options([
                                    'none' => 'No Warranty',
                                    'supplier' => 'Supplier Warranty',
                                    'manufacturer' => 'Manufacturer Warranty',
                                    'shop' => 'Shop Warranty',
                                    'replacement' => 'Replacement Warranty',
                                ])
                                ->native(false),
                            Forms\Components\TextInput::make('warranty_duration')
                                ->label('Warranty Duration')
                                ->placeholder('e.g. 7 days, 6 months, 1 year')
                                ->maxLength(255),
                        ]),
                        Forms\Components\Textarea::make('warranty_claim_process')
                            ->label('Warranty Claim Process')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                    Forms\Components\Section::make('Notes')->schema([
                        Forms\Components\Textarea::make('internal_notes')
                            ->label('Internal Notes')
                            ->helperText('Special agreement, commission rate, payment terms, or instructions.')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
                ]),

                Forms\Components\Tabs\Tab::make('Variants')->schema([
                    Forms\Components\Repeater::make('variants')
                        ->relationship()
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\Grid::make(1)->schema([
                                    Forms\Components\TextInput::make('name')->required()->label('Variant Name (e.g. Red / Large)'),
                                    Forms\Components\TextInput::make('sku')
                                        ->unique(ignoreRecord: true)
                                        ->label('SKU (Optional)')
                                        ->nullable()
                                        ->default(fn () => 'VAR-' . strtoupper(str()->random(6))),
                                ])->columnSpan(1),
                                
                                Forms\Components\FileUpload::make('image_gallery')
                                    ->label('Variant Gallery')
                                    ->multiple()
                                    ->image()
                                    ->directory('products/variants')
                                    ->reorderable()
                                    ->columnSpan(1),
                            ]),
                            
                            Forms\Components\Grid::make(4)->schema([
                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->label('Price')
                                    ->prefix('৳')
                                    ->nullable()
                                    ->helperText('Overrides base price if set.'),
                                    
                                Forms\Components\TextInput::make('compare_price')
                                    ->numeric()
                                    ->label('Compare Price')
                                    ->prefix('৳')
                                    ->nullable(),
                                    
                                Forms\Components\TextInput::make('price_modifier')
                                    ->numeric()
                                    ->default(0)
                                    ->label('Price Modifier (+/-)')
                                    ->prefix('৳')
                                    ->helperText('Used if Price is empty.'),
                                    
                                Forms\Components\Placeholder::make('discount')
                                    ->label('Discount')
                                    ->content(function (Forms\Get $get): string {
                                        $price = (float) ($get('price') ?? 0);
                                        $compare = (float) ($get('compare_price') ?? 0);
                                        
                                        if ($price > 0 && $compare > $price) {
                                            $discount = $compare - $price;
                                            $percentage = ($discount / $compare) * 100;
                                            return '৳' . number_format($discount, 2) . ' (' . number_format($percentage, 0) . '%)';
                                        }
                                        return '-';
                                    }),
                            ]),

                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('stock_quantity')->numeric()->default(0)->required()->label('Stock Quantity'),
                                Forms\Components\Toggle::make('is_active')->default(true)->label('Active'),
                            ]),
                        ])
                        ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                        ->collapsible()
                        ->defaultItems(0)
                        ->addActionLabel('Add Variant')
                ]),

                Forms\Components\Tabs\Tab::make('SEO')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('translations.en.meta_title')->label('Meta Title (EN)'),
                        Forms\Components\TextInput::make('translations.bn.meta_title')->label('Meta Title (BN)'),
                    ]),
                    Forms\Components\Textarea::make('translations.en.meta_description')->label('Meta Description (EN)')->rows(2),
                    Forms\Components\Textarea::make('translations.bn.meta_description')->label('Meta Description (BN)')->rows(2),
                ]),

            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('primaryImage.path')->label('Image')->disk('public')->circular(false)->size(50),
                Tables\Columns\TextColumn::make('translations.name')->label('Name')->searchable(),
                Tables\Columns\TextColumn::make('sku')->label('SKU')->searchable(),
                Tables\Columns\TextColumn::make('supplier_product_code')->label('Supplier Code')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('price')->label('Price')->money('BDT')->sortable(),
                Tables\Columns\TextColumn::make('stock_quantity')->label('Stock')
                    ->badge()
                    ->color(fn ($record) => $record->stock_quantity <= 0 ? 'danger' : ($record->stock_quantity <= $record->low_stock_threshold ? 'warning' : 'success')),
                Tables\Columns\TextColumn::make('category.name')->label('Category'),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
                Tables\Columns\ToggleColumn::make('is_featured')->label('Featured'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')->relationship('category', 'id'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
