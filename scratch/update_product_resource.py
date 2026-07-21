import os

file_path = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/ProductResource.php"

with open(file_path, "r") as f:
    content = f.read()

# 1. Inject Publish Status and Documents in the General Tab
general_tab_pattern = """                    Forms\\Components\\Grid::make(3)->schema([
                        Forms\\Components\\Toggle::make('is_active')->label('Active')->default(true),
                        Forms\\Components\\Toggle::make('is_featured')->label('Featured'),
                        Forms\\Components\\Toggle::make('is_new_arrival')->label('New Arrival'),
                    ]),"""
general_tab_replacement = """                    Forms\\Components\\Grid::make(3)->schema([
                        Forms\\Components\\Toggle::make('is_active')->label('Active')->default(true),
                        Forms\\Components\\Toggle::make('is_featured')->label('Featured'),
                        Forms\\Components\\Toggle::make('is_new_arrival')->label('New Arrival'),
                    ]),
                    Forms\\Components\\Section::make('Publishing')->schema([
                        Forms\\Components\\Grid::make(3)->schema([
                            Forms\\Components\\Select::make('publish_status')
                                ->label('Publish Status')
                                ->options([
                                    'Draft' => 'Draft',
                                    'Scheduled' => 'Scheduled',
                                    'Published' => 'Published',
                                    'Unpublished' => 'Unpublished',
                                    'Archived' => 'Archived',
                                ])
                                ->default('Draft')
                                ->live(),
                            Forms\\Components\\DateTimePicker::make('published_at')
                                ->label('Publish Date & Time')
                                ->visible(fn (Forms\\Get $get) => in_array($get('publish_status'), ['Scheduled', 'Published'])),
                            Forms\\Components\\DateTimePicker::make('unpublished_at')
                                ->label('Unpublish Date & Time')
                                ->visible(fn (Forms\\Get $get) => in_array($get('publish_status'), ['Scheduled', 'Published', 'Unpublished'])),
                        ]),
                    ]),
                    Forms\\Components\\Section::make('Documents')->schema([
                        Forms\\Components\\FileUpload::make('documents')
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
                    Forms\\Components\\Section::make('Activity Indicator')->schema([
                        Forms\\Components\\Grid::make(2)->schema([
                            Forms\\Components\\Placeholder::make('created_by')
                                ->label('Created By')
                                ->content(fn ($record) => $record?->createdByAdmin?->name ?? 'System'),
                            Forms\\Components\\Placeholder::make('created_at')
                                ->label('Created At')
                                ->content(fn ($record) => $record?->created_at?->format('d M Y, h:i A') ?? '-'),
                            Forms\\Components\\Placeholder::make('updated_by')
                                ->label('Last Edited By')
                                ->content(fn ($record) => $record?->updatedByAdmin?->name ?? 'System'),
                            Forms\\Components\\Placeholder::make('updated_at')
                                ->label('Last Saved Time')
                                ->content(fn ($record) => $record?->updated_at?->format('d M Y, h:i A') ?? '-'),
                        ]),
                    ]),"""

content = content.replace(general_tab_pattern, general_tab_replacement)

# 2. Inject Discount scheduling in Pricing Tab
pricing_tab_pattern = """                        Forms\\Components\\TextInput::make('compare_price')->label('Discount / Old Price (৳)')->numeric()->prefix('৳')->helperText('Used to show a discount (e.g., if price is 80 and old price is 100, 20% discount).'),"""
pricing_tab_replacement = """                        Forms\\Components\\TextInput::make('compare_price')->label('Discount / Old Price (৳)')->numeric()->prefix('৳')->helperText('Used to show a discount (e.g., if price is 80 and old price is 100, 20% discount).'),
                        Forms\\Components\\Select::make('discount_type')
                            ->label('Discount Type')
                            ->options([
                                'Fixed' => 'Fixed Amount',
                                'Percentage' => 'Percentage (%)',
                            ])
                            ->live(),
                        Forms\\Components\\TextInput::make('discount_amount')
                            ->label('Discount Amount')
                            ->numeric()
                            ->prefix(fn (Forms\\Get $get) => $get('discount_type') === 'Percentage' ? null : '৳')
                            ->suffix(fn (Forms\\Get $get) => $get('discount_type') === 'Percentage' ? '%' : null)
                            ->live(onBlur: true),
                        Forms\\Components\\Placeholder::make('final_price_preview')
                            ->label('Final Price Preview')
                            ->content(function (Forms\\Get $get) {
                                $price = (float) $get('price');
                                $discountType = $get('discount_type');
                                $discountAmount = (float) $get('discount_amount');
                                if (! $price || ! $discountAmount || ! $discountType) return '-';
                                $final = $discountType === 'Percentage' ? $price - ($price * ($discountAmount / 100)) : $price - $discountAmount;
                                return '৳' . number_format(max(0, $final), 2);
                            }),
                        Forms\\Components\\DateTimePicker::make('discount_start_date')->label('Discount Start Date'),
                        Forms\\Components\\DateTimePicker::make('discount_end_date')->label('Discount End Date'),
                        Forms\\Components\\TextInput::make('scheduled_price')->label('Scheduled Price')->numeric()->prefix('৳'),
                        Forms\\Components\\DateTimePicker::make('price_effective_date')->label('Price Effective Date'),"""

content = content.replace(pricing_tab_pattern, pricing_tab_replacement)

# 3. Update Validation for low_stock_threshold (Maximum Stock > Minimum Stock)
stock_pattern = """                        Forms\\Components\\TextInput::make('stock_quantity')
                            ->label('Stock Quantity')
                            ->numeric()
                            ->default(0)
                            ->required(),"""
stock_replacement = """                        Forms\\Components\\TextInput::make('stock_quantity')
                            ->label('Stock Quantity (Max)')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->gte('low_stock_threshold'),"""
content = content.replace(stock_pattern, stock_replacement)

threshold_pattern = """                        Forms\\Components\\TextInput::make('low_stock_threshold')
                            ->label('Low Stock Threshold')
                            ->numeric()
                            ->default(5),"""
threshold_replacement = """                        Forms\\Components\\TextInput::make('low_stock_threshold')
                            ->label('Low Stock Threshold (Min)')
                            ->numeric()
                            ->default(5)
                            ->lte('stock_quantity'),"""
content = content.replace(threshold_pattern, threshold_replacement)


with open(file_path, "w") as f:
    f.write(content)

print("Updates applied")
