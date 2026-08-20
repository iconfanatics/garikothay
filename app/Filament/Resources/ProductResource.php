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
    protected static ?string $recordTitleAttribute = 'sku';

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
                            ->options(fn () => Category::whereNull('parent_id')->with('translations')->get()->mapWithKeys(fn($c) => [$c->id => (string) ($c->name ?? 'Category #'.$c->id)]))
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
                                    return Category::with('translations')->get()->mapWithKeys(fn($c) => [$c->id => (string) ($c->name ?? 'Category #'.$c->id)]);
                                }
                                return Category::where('parent_id', $parentId)
                                    ->orWhere('id', $parentId)
                                    ->with('translations')
                                    ->get()
                                    ->mapWithKeys(fn($c) => [$c->id => (string) ($c->name ?? 'Category #'.$c->id)]);
                            })
                            ->searchable()
                            ->required(),
                    ]),
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\Select::make("brand_id")
                            ->relationship("brand", "name")
                            ->searchable()
                            ->preload()
                            ->label("Brand")
                            ->createOptionForm([
                                Forms\Components\TextInput::make("name")->required(),
                                Forms\Components\Toggle::make("is_active")->default(true)
                            ]),
                        Forms\Components\Select::make("unit_id")
                            ->relationship("unit", "name")
                            ->searchable()
                            ->preload()
                            ->label("Unit")
                            ->createOptionForm([
                                Forms\Components\TextInput::make("name")->required(),
                                Forms\Components\TextInput::make("short_name")
                            ]),
                        Forms\Components\TextInput::make('product_type')
                            ->label('Product Type')
                            ->placeholder('e.g. Parts, Accessories, Tool')
                            ->maxLength(255),
                    ]),
                    Forms\Components\Select::make("tags")
                        ->relationship("tags", "name")
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->label("Product Tags")
                        ->createOptionForm([
                            Forms\Components\TextInput::make("name")->required(),
                        ]),
                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(255)
                        ->dehydrateStateUsing(fn (?string $state) => \Illuminate\Support\Str::slug($state ?? ''))
                        ->unique(Product::class, 'slug', ignoreRecord: true)
                        ->readOnly(fn (string $operation): bool => $operation === 'edit'),
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
                    Forms\Components\Section::make('Product FAQs')->schema([
                        Forms\Components\Repeater::make('faqs')
                            ->label('Frequently Asked Questions')
                            ->schema([
                                Forms\Components\TextInput::make('question')->required()->label('Question (e.g. Is this battery maintenance free?)')->maxLength(255),
                                Forms\Components\Textarea::make('answer')->required()->label('Answer')->rows(2),
                            ])
                            ->collapsible()
                            ->defaultItems(0)
                            ->addActionLabel('Add FAQ')
                            ->columnSpanFull(),
                    ]),
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
                    Forms\Components\TextInput::make('video_url')
                        ->label('Product Video URL')
                        ->placeholder('e.g. YouTube or Vimeo link')
                        ->url()
                        ->maxLength(2048)
                        ->columnSpanFull(),
                    Forms\Components\Grid::make(4)->schema([
                        Forms\Components\Toggle::make('is_active')->label('Active')->default(true)
                            ->disabled(fn () => auth()->user()?->hasRole('Shop Manager')),
                        Forms\Components\Toggle::make('is_featured')->label('Featured'),
                        Forms\Components\Toggle::make('is_new_arrival')->label('New Arrival'),
                        Forms\Components\Toggle::make('is_preorder')->label('Pre-Order'),
                    ]),
                    Forms\Components\Section::make('Publishing')->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Select::make('publish_status')
                                ->label('Publish Status')
                                ->options([
                                    'Draft' => 'Draft',
                                    'Scheduled' => 'Scheduled',
                                    'Published' => 'Published',
                                    'Unpublished' => 'Unpublished',
                                    'Archived' => 'Archived',
                                ])
                                ->default('Published')
                                ->live(),
                            Forms\Components\DateTimePicker::make('published_at')
                                ->label('Publish Date & Time')
                                ->visible(fn (Forms\Get $get) => in_array($get('publish_status'), ['Scheduled', 'Published']))
                                ->minDate(now())
                                ->rule(function (Forms\Get $get, ?Product $record) {
                                    return function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                                        $valueDate = \Carbon\Carbon::parse($value);
                                        if (!$record || $record->published_at?->format('Y-m-d H:i') !== $valueDate->format('Y-m-d H:i')) {
                                            if ($valueDate->isPast()) {
                                                $fail('Publish date & time cannot be in the past.');
                                            }
                                        }
                                    };
                                }),
                            Forms\Components\DateTimePicker::make('unpublished_at')
                                ->label('Unpublish Date & Time')
                                ->visible(fn (Forms\Get $get) => in_array($get('publish_status'), ['Scheduled', 'Published', 'Unpublished']))
                                ->minDate(now())
                                ->after('published_at')
                                ->rule(function (Forms\Get $get, ?Product $record) {
                                    return function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                                        $valueDate = \Carbon\Carbon::parse($value);
                                        if (!$record || $record->unpublished_at?->format('Y-m-d H:i') !== $valueDate->format('Y-m-d H:i')) {
                                            if ($valueDate->isPast()) {
                                                $fail('Unpublish date & time cannot be in the past.');
                                            }
                                        }
                                    };
                                }),
                        ]),
                    ]),
                    Forms\Components\Section::make('Documents')->schema([
                        Forms\Components\FileUpload::make('documents')
                            ->label('Product Documents (PDF)')
                            ->helperText('Upload User Manuals, Installation Guides, etc.')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120)
                            ->multiple()
                            ->reorderable()
                            ->disk('public')
                            ->directory('product-documents')
                            ->visibility('public')
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),
                    Forms\Components\Section::make('Activity Indicator')->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Placeholder::make('created_by')
                                ->label('Created By')
                                ->content(fn ($record) => $record ? ($record->createdByAdmin?->name ?? 'System') : (auth('admin')->user()?->name ?? 'System')),
                            Forms\Components\Placeholder::make('created_at')
                                ->label('Created At')
                                ->content(fn ($record) => $record?->created_at?->format('d M Y, h:i A') ?? '-'),
                            Forms\Components\Placeholder::make('updated_by')
                                ->label('Last Edited By')
                                ->content(fn ($record) => $record ? ($record->updatedByAdmin?->name ?? 'System') : '-'),
                            Forms\Components\Placeholder::make('updated_at')
                                ->label('Last Saved Time')
                                ->content(fn ($record) => $record?->updated_at?->format('d M Y, h:i A') ?? '-'),
                        ]),
                    ]),
                ]),

                Forms\Components\Tabs\Tab::make('Features & Attributes')->schema([
                    Forms\Components\Repeater::make('features')
                        ->label('Product Features')
                        ->schema([
                            Forms\Components\TextInput::make('feature')->required()->label('Feature (e.g. 100% Authentic)'),
                        ])
                        ->collapsible()
                        ->addActionLabel('Add Feature')
                        ->columnSpanFull(),

                    Forms\Components\KeyValue::make('custom_fields')
                        ->label('Custom Fields (Specifications)')
                        ->keyLabel('Property (e.g. Model, Warranty)')
                        ->valueLabel('Value (e.g. Corolla 2020, 1 Year)')
                        ->columnSpanFull(),

                    Forms\Components\TagsInput::make('collections')
                        ->label('Collections')
                        ->placeholder('e.g. Winter Sale, Trending')
                        ->separator(',')
                        ->columnSpanFull(),

                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Repeater::make('highlights')
                            ->label('Product Icons / Highlights')
                            ->schema([
                                Forms\Components\FileUpload::make('icon')->label('Icon Image')->image()->maxSize(512)->directory('products/icons'),
                                Forms\Components\TextInput::make('text')->label('Highlight Text'),
                            ])
                            ->collapsible()
                            ->addActionLabel('Add Highlight'),

                        Forms\Components\Repeater::make('certifications')
                            ->label('Certifications')
                            ->schema([
                                Forms\Components\FileUpload::make('image')->label('Certification Logo')->image()->maxSize(512)->directory('products/certifications'),
                                Forms\Components\TextInput::make('name')->label('Certification Name'),
                            ])
                            ->collapsible()
                            ->addActionLabel('Add Certification'),
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
                            ->live(onBlur: true)
                            ->disabled(fn () => auth()->user()?->hasRole('Shop Manager')),
                        Forms\Components\TextInput::make('price')
                            ->label('Selling Price (৳)')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->prefix('৳')
                            ->live(onBlur: true)
                            ->disabled(fn () => auth()->user()?->hasRole('Shop Manager'))
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                if ($state === null || $state === '') return;
                                $cost = (float) $get('cost_price');
                                $minSelling = (float) $get('minimum_selling_price');
                                $val = (float) $state;
                                $minAllowed = max($cost, $minSelling);
                                if ($minAllowed > 0 && $val < $minAllowed) {
                                    $set('price', $minAllowed);
                                    \Filament\Notifications\Notification::make()->warning()->title('Selling price automatically adjusted to the minimum allowed value ('.number_format($minAllowed, 2).').')->send();
                                }
                            })
                            ->rule(function (Forms\Get $get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $cost = (float) $get('cost_price');
                                    $minSelling = (float) $get('minimum_selling_price');
                                    if ($cost > 0 && $value < $cost) {
                                        $fail('Selling price cannot be less than Supplier Price.');
                                    }
                                    if ($minSelling > 0 && $value < $minSelling) {
                                        $fail('Selling price cannot be less than Minimum Selling Price.');
                                    }
                                };
                            }),
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
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                if ($state === null || $state === '') return;
                                $cost = (float) $get('cost_price');
                                $minSelling = (float) $get('minimum_selling_price');
                                $price = (float) $get('price');
                                $minAllowedPrice = max($cost, $minSelling);
                                $val = (float) $state;
                                
                                if ($get('discount_type') === 'Percentage') {
                                    if ($val > 100) {
                                        $set('discount_amount', 100);
                                        $val = 100;
                                        \Filament\Notifications\Notification::make()->warning()->title('Percentage cannot exceed 100.')->send();
                                    }
                                    $discountValue = $price * ($val / 100);
                                    if ($minAllowedPrice > 0 && ($price - $discountValue) < $minAllowedPrice) {
                                        $maxPercentage = $price > 0 ? (($price - $minAllowedPrice) / $price) * 100 : 0;
                                        $set('discount_amount', round(max(0, $maxPercentage), 2));
                                        \Filament\Notifications\Notification::make()->warning()->title('Discount automatically adjusted to maintain minimum profit margin.')->send();
                                    }
                                } else {
                                    if ($val >= $price && $price > 0) {
                                        $set('discount_amount', $price - 1);
                                        $val = $price - 1;
                                        \Filament\Notifications\Notification::make()->warning()->title('Fixed discount adjusted to be less than selling price.')->send();
                                    }
                                    if ($minAllowedPrice > 0 && ($price - $val) < $minAllowedPrice) {
                                        $set('discount_amount', max(0, $price - $minAllowedPrice));
                                        \Filament\Notifications\Notification::make()->warning()->title('Discount automatically adjusted to maintain minimum profit margin.')->send();
                                    }
                                }
                            })
                            ->rule(function (Forms\Get $get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    if ($get('discount_type') === 'Percentage' && $value > 100) {
                                        $fail('Percentage cannot exceed 100.');
                                    }
                                    if ($get('discount_type') === 'Fixed' && $value >= (float) $get('price')) {
                                        $fail('Fixed discount must be less than the selling price.');
                                    }
                                    $cost = (float) $get('cost_price');
                                    $minSelling = (float) $get('minimum_selling_price');
                                    $price = (float) $get('price');
                                    $discount = $get('discount_type') === 'Percentage' ? ($price * ($value / 100)) : (float) $value;
                                    $finalPrice = $price - $discount;
                                    if ($cost > 0 && $finalPrice < $cost) {
                                        $fail('Final price after discount cannot be less than Supplier Price.');
                                    }
                                    if ($minSelling > 0 && $finalPrice < $minSelling) {
                                        $fail('Final price after discount cannot be less than Minimum Selling Price.');
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
                            ->minDate(now()),
                        Forms\Components\DateTimePicker::make('discount_end_date')
                            ->label('Discount End Date')
                            ->minDate(now())
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                $start = $get('discount_start_date');
                                if ($start && $state && \Carbon\Carbon::parse($state)->isBefore(\Carbon\Carbon::parse($start))) {
                                    $set('discount_end_date', null);
                                    \Filament\Notifications\Notification::make()->warning()->title('Discount End Date cannot be before Start Date.')->send();
                                }
                            })
                            ->afterOrEqual('discount_start_date'),
                        Forms\Components\TextInput::make('scheduled_price')
                            ->label('Scheduled Price')
                            ->numeric()
                            ->prefix('৳')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                if ($state === null || $state === '') return;
                                $cost = (float) $get('cost_price');
                                $minSelling = (float) $get('minimum_selling_price');
                                $val = (float) $state;
                                $minAllowed = max($cost, $minSelling);
                                if ($minAllowed > 0 && $val < $minAllowed) {
                                    $set('scheduled_price', $minAllowed);
                                    \Filament\Notifications\Notification::make()->warning()->title('Scheduled price automatically adjusted to the minimum allowed value ('.number_format($minAllowed, 2).').')->send();
                                }
                            })
                            ->rule(function (Forms\Get $get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $cost = (float) $get('cost_price');
                                    $minSelling = (float) $get('minimum_selling_price');
                                    if ($cost > 0 && $value < $cost) {
                                        $fail('Scheduled price cannot be less than Supplier Price.');
                                    }
                                    if ($minSelling > 0 && $value < $minSelling) {
                                        $fail('Scheduled price cannot be less than Minimum Selling Price.');
                                    }
                                };
                            }),
                        Forms\Components\DateTimePicker::make('price_effective_date')
                            ->label('Price Effective Date')
                            ->minDate(now()),
                        Forms\Components\TextInput::make('minimum_selling_price')
                            ->label('Minimum Selling Price (৳)')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('৳')
                            ->live(onBlur: true)
                            ->disabled(fn () => auth()->user()?->hasRole('Shop Manager'))
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                if ($state === null || $state === '') return;
                                $cost = (float) $get('cost_price');
                                $val = (float) $state;
                                if ($cost > 0 && $val < $cost) {
                                    $set('minimum_selling_price', $cost);
                                    \Filament\Notifications\Notification::make()->warning()->title('Minimum selling price automatically adjusted to match Supplier Price.')->send();
                                }
                            })
                            ->rule(function (Forms\Get $get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $cost = (float) $get('cost_price');
                                    if ($cost > 0 && $value < $cost) {
                                        $fail('Minimum selling price cannot be less than Supplier Price.');
                                    }
                                };
                            }),
                        Forms\Components\Placeholder::make('profit_margin_preview')
                            ->label('Profit Margin')
                            ->content(function (Forms\Get $get): string {
                                $supplierPrice = (float) ($get('cost_price') ?? 0);
                                $sellingPrice = (float) ($get('price') ?? 0);
                                $discountType = $get('discount_type');
                                $discountAmount = (float) ($get('discount_amount') ?? 0);

                                if ($sellingPrice <= 0 || $get('cost_price') === null || $get('cost_price') === '') {
                                    return 'Enter supplier and selling prices';
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
                    Forms\Components\Grid::make(4)->schema([
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->helperText('Leave blank to generate automatically, for example GK-BCDf34.')
                            ->placeholder('Auto-generated if blank')
                            ->nullable()
                            ->readOnly(fn (string $operation): bool => $operation === 'edit')
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('stock_quantity')->label('Total Stock')->numeric()->required()->default(0),
                        Forms\Components\TextInput::make('reserved_stock')->label('Reserved Stock')->numeric()->default(0)->helperText('Booked but not shipped'),
                        Forms\Components\TextInput::make('low_stock_threshold')->label('Low Stock Alert')->numeric()->default(5),
                    ]),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Select::make('weight_unit')
                                ->label('Weight Unit')
                                ->options([
                                    'g' => 'Grams (g)',
                                    'kg' => 'Kilograms (kg)'
                                ])
                                ->default('g')
                                ->live()
                                ->dehydrated(false)
                                ->afterStateHydrated(function (Forms\Components\Select $component, $state, ?Product $record, Forms\Set $set) {
                                    if ($record && $record->weight_grams) {
                                        if ($record->weight_grams >= 1000 && $record->weight_grams % 1000 === 0) {
                                            $component->state('kg');
                                            $set('weight_value', $record->weight_grams / 1000);
                                        } else {
                                            $component->state('g');
                                            $set('weight_value', $record->weight_grams);
                                        }
                                    }
                                }),
                            Forms\Components\TextInput::make('weight_value')
                                ->label('Weight Value')
                                ->numeric()
                                ->dehydrated(false),
                            Forms\Components\Hidden::make('weight_grams')
                                ->dehydrateStateUsing(function ($state, Forms\Get $get) {
                                    $val = (float) $get('weight_value');
                                    return $get('weight_unit') === 'kg' ? $val * 1000 : $val;
                                }),
                        ])->columnSpan(1),
                        Forms\Components\TextInput::make('tax_rate')->label('Tax Rate (%)')->numeric()->default(0)->columnSpan(1),
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
                                ->disabled(fn (Forms\Get $get) => $get('shipping_restriction') === 'pickup_only')
                                ->dehydrated()
                                ->helperText('Disable for digital products or services.'),
                            Forms\Components\Select::make('shipping_restriction')
                                ->label('Shipping Restriction')
                                ->options([
                                    'home_delivery' => 'Home Delivery Available',
                                    'pickup_only' => 'Pickup Only',
                                    'courier_restricted' => 'Courier Restricted',
                                ])
                                ->default('home_delivery')
                                ->native(false)
                                ->live()
                                ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                    if ($state === 'pickup_only') {
                                        $set('requires_shipping', false);
                                        $set('is_free_shipping_eligible', false);
                                        $set('has_special_handling', false);
                                    }
                                }),
                            Forms\Components\Toggle::make('is_free_shipping_eligible')
                                ->label('Free Shipping Eligible')
                                ->disabled(fn (Forms\Get $get) => $get('shipping_restriction') === 'pickup_only')
                                ->dehydrated()
                                ->default(false),
                        ]),
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Toggle::make('has_special_handling')
                                ->label('Dangerous / Special Handling')
                                ->default(false)
                                ->disabled(fn (Forms\Get $get) => $get('shipping_restriction') === 'pickup_only')
                                ->dehydrated()
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
                                ->live()
                                ->native(false),
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('warranty_duration_value')
                                    ->label('Warranty Duration Value')
                                    ->numeric()
                                    ->visible(fn (Forms\Get $get) => $get('warranty_type') !== 'none')
                                    ->dehydrated(false)
                                    ->afterStateHydrated(function (Forms\Components\TextInput $component, ?Product $record, Forms\Set $set) {
                                        if ($record && $record->warranty_duration) {
                                            preg_match('/^(\d+)\s*(.*)$/', $record->warranty_duration, $matches);
                                            if (count($matches) === 3) {
                                                $component->state($matches[1]);
                                                $set('warranty_duration_period', ucfirst($matches[2]));
                                            } else {
                                                $component->state($record->warranty_duration);
                                            }
                                        }
                                    }),
                                Forms\Components\Select::make('warranty_duration_period')
                                    ->label('Warranty Period')
                                    ->options(['Days' => 'Days', 'Months' => 'Months', 'Years' => 'Years'])
                                    ->visible(fn (Forms\Get $get) => $get('warranty_type') !== 'none')
                                    ->dehydrated(false),
                                Forms\Components\Hidden::make('warranty_duration')
                                    ->dehydrateStateUsing(function ($state, Forms\Get $get) {
                                        if ($get('warranty_type') === 'none' || !$get('warranty_duration_value')) return null;
                                        return $get('warranty_duration_value') . ' ' . $get('warranty_duration_period');
                                    }),
                            ])->visible(fn (Forms\Get $get) => $get('warranty_type') !== 'none'),
                        ]),
                        Forms\Components\Textarea::make('warranty_claim_process')
                            ->label('Warranty Claim Process')
                            ->rows(3)
                            ->visible(fn (Forms\Get $get) => $get('warranty_type') !== 'none')
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
            ->defaultPaginationPageOption(50)
            ->paginationPageOptions([50, 100, 250, 'all'])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Product ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make('primary_image')
                    ->label('Thumbnail')
                    ->state(function (Product $record) {
                        return $record->images->first()?->path;
                    })
                    ->square(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Brand')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('translations.name')
                    ->label('Name')
                    ->formatStateUsing(fn ($record) => $record->translations->firstWhere('locale', 'en')?->name ?? 'N/A')
                    ->searchable()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy(
                            \App\Models\ProductTranslation::select('name')
                                ->whereColumn('product_translations.product_id', 'products.id')
                                ->where('locale', 'en')
                                ->limit(1),
                            $direction
                        );
                    }),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('BDT')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('publish_status')
                    ->label('Status')
                    ->colors([
                        'danger' => 'Archived',
                        'warning' => 'Draft',
                        'success' => 'Published',
                        'primary' => 'Scheduled',
                        'secondary' => 'Unpublished',
                    ])
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created Date')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('supplier_id')
                    ->label('Supplier')
                    ->relationship('supplier', 'name'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status (Active/Inactive)')
                    ->boolean()
                    ->trueLabel('Active')
                    ->falseLabel('Inactive'),
                Tables\Filters\TernaryFilter::make('is_discounted')
                    ->label('Discounted Product')
                    ->placeholder('All')
                    ->trueLabel('Yes')
                    ->falseLabel('No')
                    ->queries(
                        true: fn (\Illuminate\Database\Eloquent\Builder $query) => $query->whereNotNull('discount_amount')->where('discount_amount', '>', 0),
                        false: fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where(fn ($q) => $q->whereNull('discount_amount')->orWhere('discount_amount', '<=', 0)),
                        blank: fn (\Illuminate\Database\Eloquent\Builder $query) => $query,
                    ),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name),
                Tables\Filters\SelectFilter::make('brand')
                    ->label('Brand')
                    ->options(fn () => \App\Models\Product::query()->distinct()->whereNotNull('brand')->pluck('brand', 'brand')->toArray()),
                Tables\Filters\SelectFilter::make('publish_status')
                    ->label('Publish Status')
                    ->options([
                        'Draft' => 'Draft',
                        'Scheduled' => 'Scheduled',
                        'Published' => 'Published',
                        'Unpublished' => 'Unpublished',
                        'Archived' => 'Archived',
                    ]),
                Tables\Filters\TernaryFilter::make('is_preorder')
                    ->label('Pre-Order Status'),
                Tables\Filters\SelectFilter::make('stock_level')
                    ->label('Stock Level')
                    ->options([
                        'out_of_stock' => 'Out of Stock',
                        'low_stock' => 'Low Stock',
                        'in_stock' => 'In Stock',
                        'available_stock' => 'Available Stock',
                        'reserved_stock' => 'Reserved Stock',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $val = $data['value'] ?? null;
                        return $query
                            ->when(
                                $val === 'out_of_stock',
                                fn (Builder $query): Builder => $query->where('stock_quantity', '<=', 0)->whereDoesntHave('variants', fn($q) => $q->where('stock_quantity', '>', 0)),
                            )
                            ->when(
                                $val === 'low_stock',
                                fn (Builder $query): Builder => $query->where(function($q) {
                                    $q->whereColumn('stock_quantity', '<=', 'low_stock_threshold')->where('stock_quantity', '>', 0)
                                      ->orWhereHas('variants', fn($vq) => $vq->whereColumn('stock_quantity', '<=', 'low_stock_threshold')->where('stock_quantity', '>', 0));
                                }),
                            )
                            ->when(
                                $val === 'in_stock',
                                fn (Builder $query): Builder => $query->where(function($q) {
                                    $q->where('stock_quantity', '>', 0)
                                      ->orWhereHas('variants', fn($vq) => $vq->where('stock_quantity', '>', 0));
                                }),
                            )
                            ->when(
                                $val === 'available_stock',
                                fn (Builder $query): Builder => $query->where(function($q) {
                                    $q->whereRaw('products.stock_quantity > IFNULL(products.reserved_stock, 0)')
                                      ->orWhereHas('variants', fn($vq) => $vq->where('product_variants.stock_quantity', '>', 0));
                                }),
                            )
                            ->when(
                                $val === 'reserved_stock',
                                fn (Builder $query): Builder => $query->where('reserved_stock', '>', 0),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view_product')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (\App\Models\Product $record): string => route('shop.show', $record->slug))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publish')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => true, 'publish_status' => 'Published'])),
                    Tables\Actions\BulkAction::make('unpublish')
                        ->label('Unpublish')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => false, 'publish_status' => 'Unpublished'])),
                    Tables\Actions\BulkAction::make('archive')
                        ->label('Archive')
                        ->icon('heroicon-o-archive-box')
                        ->action(fn ($records) => $records->each->update(['publish_status' => 'Archived'])),
                    Tables\Actions\BulkAction::make('change_category')
                        ->label('Change Category')
                        ->icon('heroicon-o-tag')
                        ->form([
                            Forms\Components\Select::make('category_id')
                                ->label('New Category')
                                ->options(Category::with('translations')->get()->mapWithKeys(fn($c) => [$c->id => (string) ($c->name ?? 'Category #'.$c->id)]))
                                ->required(),
                        ])
                        ->action(fn ($records, array $data) => $records->each->update(['category_id' => $data['category_id']])),
                    Tables\Actions\BulkAction::make('change_brand')
                        ->label('Change Brand')
                        ->icon('heroicon-o-briefcase')
                        ->form([
                            Forms\Components\TextInput::make('brand')
                                ->label('New Brand')
                                ->required(),
                        ])
                        ->action(fn ($records, array $data) => $records->each->update(['brand' => $data['brand']])),
                    Tables\Actions\BulkAction::make('download')
                        ->label('Download Export (CSV)')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($records) {
                            $headers = array(
                                "Content-type"        => "text/csv",
                                "Content-Disposition" => "attachment; filename=products_export.csv",
                                "Pragma"              => "no-cache",
                                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                                "Expires"             => "0"
                            );
                            $columns = array('ID', 'Name', 'SKU', 'Price', 'Stock', 'Status');
                            $callback = function() use($records, $columns) {
                                $file = fopen('php://output', 'w');
                                fputcsv($file, $columns);
                                foreach ($records as $record) {
                                    fputcsv($file, array($record->id, $record->name, $record->sku, $record->price, $record->stock_quantity, $record->publish_status));
                                }
                                fclose($file);
                            };
                            return response()->stream($callback, 200, $headers);
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProductResource\RelationManagers\VariantsRelationManager::class,
        ];
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
