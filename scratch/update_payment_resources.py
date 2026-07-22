import os

def replace_content(filepath, search, replace):
    with open(filepath, 'r') as f:
        content = f.read()
    content = content.replace(search, replace)
    with open(filepath, 'w') as f:
        f.write(content)


# 1. Update PaymentGatewayResource
pg_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/PaymentGatewayResource.php"
pg_form = """    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\\Components\\Section::make('Gateway Details')->schema([
                    Forms\\Components\\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\\Components\\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    Forms\\Components\\Select::make('mode')
                        ->options([
                            'sandbox' => 'Sandbox (Testing)',
                            'live' => 'Live (Production)',
                        ])
                        ->required()
                        ->default('sandbox'),
                    Forms\\Components\\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ])->columns(2),
                Forms\\Components\\Section::make('Credentials (JSON)')
                    ->description('Store API keys, secrets, and store IDs here in JSON format.')
                    ->schema([
                    Forms\\Components\\KeyValue::make('credentials')
                        ->label('API Credentials')
                        ->keyLabel('Key Name (e.g. store_id)')
                        ->valueLabel('Value'),
                ]),
            ]);
    }"""
pg_table = """    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\\Columns\\TextColumn::make('name')
                    ->searchable(),
                Tables\\Columns\\TextColumn::make('slug')
                    ->searchable(),
                Tables\\Columns\\BadgeColumn::make('mode')
                    ->colors([
                        'warning' => 'sandbox',
                        'success' => 'live',
                    ]),
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
            ])
            ->bulkActions([
                Tables\\Actions\\BulkActionGroup::make([
                    Tables\\Actions\\DeleteBulkAction::make(),
                ]),
            ]);
    }"""

replace_content(pg_file, "protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';", "protected static ?string $navigationIcon = 'heroicon-o-credit-card';\n    protected static ?string $navigationGroup = 'Payment Management';")
replace_content(pg_file, """    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }""", pg_form)
replace_content(pg_file, """    public static function table(Table $table): Table
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
    }""", pg_table)


# 2. Update PaymentResource
pay_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/PaymentResource.php"
pay_form = """    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\\Components\\Section::make('Payment Details')->schema([
                    Forms\\Components\\Select::make('order_id')
                        ->relationship('order', 'order_number')
                        ->searchable()
                        ->required(),
                    Forms\\Components\\TextInput::make('transaction_id')
                        ->required()
                        ->maxLength(255),
                    Forms\\Components\\TextInput::make('payment_method')
                        ->required()
                        ->maxLength(255),
                    Forms\\Components\\TextInput::make('amount')
                        ->required()
                        ->numeric(),
                    Forms\\Components\\TextInput::make('currency')
                        ->required()
                        ->default('BDT')
                        ->maxLength(3),
                    Forms\\Components\\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'completed' => 'Completed',
                            'failed' => 'Failed',
                            'refunded' => 'Refunded',
                        ])
                        ->required()
                        ->default('pending'),
                    Forms\\Components\\DateTimePicker::make('paid_at'),
                ])->columns(2),
                Forms\\Components\\Section::make('Gateway Response')->schema([
                    Forms\\Components\\KeyValue::make('gateway_response')
                        ->label('Raw Gateway Data'),
                ])
            ]);
    }"""
pay_table = """    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\\Columns\\TextColumn::make('order.order_number')
                    ->label('Order Number')
                    ->searchable()
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('transaction_id')
                    ->searchable(),
                Tables\\Columns\\TextColumn::make('payment_method')
                    ->searchable(),
                Tables\\Columns\\TextColumn::make('amount')
                    ->money(fn ($record) => $record->currency ?? 'BDT')
                    ->sortable(),
                Tables\\Columns\\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'failed',
                        'secondary' => 'refunded',
                    ]),
                Tables\\Columns\\TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\\Filters\\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
                Tables\\Filters\\SelectFilter::make('payment_method')
                    ->options(function () {
                        return \\App\\Models\\PaymentGateway::pluck('name', 'slug')->toArray();
                    }),
            ])
            ->actions([
                Tables\\Actions\\ViewAction::make(),
                Tables\\Actions\\EditAction::make(),
            ])
            ->bulkActions([
                Tables\\Actions\\BulkActionGroup::make([
                    Tables\\Actions\\DeleteBulkAction::make(),
                ]),
            ]);
    }"""

replace_content(pay_file, "protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';", "protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';\n    protected static ?string $navigationGroup = 'Payment Management';\n    protected static ?string $modelLabel = 'Transaction';\n    protected static ?string $pluralModelLabel = 'Transactions';")
replace_content(pay_file, """    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }""", pay_form)
replace_content(pay_file, """    public static function table(Table $table): Table
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
    }""", pay_table)

print("Payment Resources patched!")
