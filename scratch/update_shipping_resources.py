import os

def replace_content(filepath, search, replace):
    with open(filepath, 'r') as f:
        content = f.read()
    content = content.replace(search, replace)
    with open(filepath, 'w') as f:
        f.write(content)

# 1. Update ShippingZoneResource
zone_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/ShippingZoneResource.php"
zone_form = """    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\\Components\\Section::make('Zone Details')->schema([
                    Forms\\Components\\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    Forms\\Components\\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ]),
            ]);
    }"""
zone_table = """    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\\Columns\\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('shipping_methods_count')
                    ->label('Shipping Methods')
                    ->counts('shippingMethods')
                    ->badge(),
                Tables\\Columns\\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\\Columns\\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\\Columns\\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\\Actions\\EditAction::make(),
                Tables\\Actions\\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\\Actions\\BulkActionGroup::make([
                    Tables\\Actions\\DeleteBulkAction::make(),
                ]),
            ]);
    }"""

replace_content(zone_file, "protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';", "protected static ?string $navigationIcon = 'heroicon-o-map';\n    protected static ?string $navigationGroup = 'Settings';")
replace_content(zone_file, """    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }""", zone_form)
replace_content(zone_file, """    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }""", zone_table)

# 2. Update ShippingMethodResource
method_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/ShippingMethodResource.php"
method_form = """    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\\Components\\Section::make('Method Details')->schema([
                    Forms\\Components\\Select::make('shipping_zone_id')
                        ->relationship('shippingZone', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    Forms\\Components\\TextInput::make('name')
                        ->label('Method Name (e.g. Standard, Express)')
                        ->required()
                        ->maxLength(255),
                ])->columns(2),
                Forms\\Components\\Section::make('Pricing Rules')->schema([
                    Forms\\Components\\TextInput::make('base_charge')
                        ->label('Base Charge (BDT)')
                        ->numeric()
                        ->default(0.00)
                        ->required(),
                    Forms\\Components\\TextInput::make('free_shipping_threshold')
                        ->label('Free Shipping Threshold (Order Amount)')
                        ->helperText('If order total is above this amount, shipping will be free. Leave blank for no free shipping.')
                        ->numeric()
                        ->nullable(),
                ])->columns(2),
                Forms\\Components\\Section::make('Status')->schema([
                    Forms\\Components\\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ]),
            ]);
    }"""
method_table = """    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\\Columns\\TextColumn::make('shippingZone.name')
                    ->label('Zone')
                    ->sortable()
                    ->searchable(),
                Tables\\Columns\\TextColumn::make('name')
                    ->label('Method')
                    ->searchable()
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('base_charge')
                    ->label('Base Charge')
                    ->money('BDT')
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('free_shipping_threshold')
                    ->label('Free Shipping Over')
                    ->money('BDT')
                    ->placeholder('N/A')
                    ->sortable(),
                Tables\\Columns\\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\\Columns\\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\\Columns\\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('shipping_zone_id')
            ->groups([
                'shippingZone.name',
            ])
            ->filters([
                Tables\\Filters\\SelectFilter::make('shipping_zone_id')
                    ->relationship('shippingZone', 'name')
                    ->label('Zone'),
            ])
            ->actions([
                Tables\\Actions\\EditAction::make(),
                Tables\\Actions\\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\\Actions\\BulkActionGroup::make([
                    Tables\\Actions\\DeleteBulkAction::make(),
                ]),
            ]);
    }"""

replace_content(method_file, "protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';", "protected static ?string $navigationIcon = 'heroicon-o-truck';\n    protected static ?string $navigationGroup = 'Settings';")
replace_content(method_file, """    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }""", method_form)
replace_content(method_file, """    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }""", method_table)

print("Shipping Resources patched!")
