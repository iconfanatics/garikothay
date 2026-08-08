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
                Forms\Components\Tabs::make('Supplier Details')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Supplier Information')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Supplier Name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('company_name')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('contact_person')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('contact_number')
                                    ->tel()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('alternative_contact_number')
                                    ->tel()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('website')
                                    ->url()
                                    ->maxLength(255),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Business Information')
                            ->schema([
                                Forms\Components\Select::make('business_type')
                                    ->options([
                                        'Manufacturer' => 'Manufacturer',
                                        'Distributor' => 'Distributor',
                                        'Importer' => 'Importer',
                                        'Wholesaler' => 'Wholesaler',
                                    ]),
                                Forms\Components\TextInput::make('division')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('district')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('postal_code')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('trade_license_no')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('bin_vat_no')
                                    ->maxLength(255),
                                Forms\Components\FileUpload::make('visiting_card_image')
                                    ->image()
                                    ->directory('suppliers/visiting-cards')
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('address')
                                    ->maxLength(65535)
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Payment Terms & Banking')
                            ->schema([
                                Forms\Components\Select::make('payment_terms')
                                    ->options([
                                        'Cash' => 'Cash',
                                        '7 Days' => '7 Days',
                                        '15 Days' => '15 Days',
                                        '30 Days' => '30 Days',
                                    ]),
                                Forms\Components\TextInput::make('minimum_order_quantity')
                                    ->label('MOQ')
                                    ->numeric(),
                                Forms\Components\TextInput::make('bank_name')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('account_name')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('account_number')
                                    ->maxLength(255),
                                Forms\Components\Select::make('mobile_banking_provider')
                                    ->options([
                                        'bKash' => 'bKash',
                                        'Nagad' => 'Nagad',
                                        'Rocket' => 'Rocket',
                                    ]),
                                Forms\Components\TextInput::make('mobile_banking_number')
                                    ->tel()
                                    ->maxLength(255),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Logistics & Purchase Settings')
                            ->schema([
                                Forms\Components\Textarea::make('pickup_address')
                                    ->maxLength(65535)
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('delivery_coverage')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('preferred_courier')
                                    ->maxLength(255),
                                Forms\Components\Toggle::make('supports_return')
                                    ->default(false),
                                Forms\Components\Toggle::make('warranty_support')
                                    ->default(false),
                                Forms\Components\TextInput::make('average_delivery_time_days')
                                    ->label('Avg Delivery Time (Days)')
                                    ->numeric(),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Contact & Communication')
                            ->schema([
                                Forms\Components\TextInput::make('whatsapp_number')
                                    ->tel()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('facebook_page')
                                    ->url()
                                    ->maxLength(255),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Internal')
                            ->schema([
                                Forms\Components\Select::make('account_manager_id')
                                    ->relationship('accountManager', 'name')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\DatePicker::make('last_contact_date'),
                                Forms\Components\DatePicker::make('last_purchase_date'),
                                Forms\Components\Textarea::make('remarks')
                                    ->label('Remarks / Notes (Admin Only)')
                                    ->maxLength(65535)
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Settings & Auto Info')
                            ->schema([
                                Forms\Components\Toggle::make('preferred_supplier')
                                    ->default(false),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Active Supplier')
                                    ->default(true),
                                Forms\Components\TextInput::make('supplier_code')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->helperText('Auto-generated on creation.'),
                                Forms\Components\Textarea::make('notes')
                                    ->maxLength(65535)
                                    ->columnSpanFull(),
                                Forms\Components\Placeholder::make('total_products')
                                    ->label('Total Products')
                                    ->content(fn ($record) => $record?->products()->count() ?? 0),
                                Forms\Components\TextInput::make('total_purchase_orders')
                                    ->label('Total Purchase Orders')
                                    ->numeric()
                                    ->default(0),
                                Forms\Components\TextInput::make('total_purchase_amount')
                                    ->label('Total Purchase Amount')
                                    ->numeric()
                                    ->prefix('৳')
                                    ->default(0.00),
                                Forms\Components\TextInput::make('outstanding_due')
                                    ->label('Outstanding Due')
                                    ->numeric()
                                    ->prefix('৳')
                                    ->default(0.00),
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Created At')
                                    ->content(fn ($record) => $record?->created_at ? $record->created_at->format('d M Y, h:i A') : '-'),
                            ])->columns(2),
                    ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->paginationPageOptions([50, 100, 250, 'all'])
            ->columns([
                Tables\Columns\TextColumn::make('supplier_code')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_name')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('business_type')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
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
                Tables\Columns\IconColumn::make('preferred_supplier')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All')
                    ->trueLabel('Active Suppliers')
                    ->falseLabel('Deactive Suppliers'),
                Tables\Filters\SelectFilter::make('business_type')
                    ->options([
                        'Manufacturer' => 'Manufacturer',
                        'Distributor' => 'Distributor',
                        'Importer' => 'Importer',
                        'Wholesaler' => 'Wholesaler',
                    ]),
                Tables\Filters\TernaryFilter::make('preferred_supplier')
                    ->label('Preferred Supplier')
                    ->placeholder('All')
                    ->trueLabel('Preferred Only')
                    ->falseLabel('Non-Preferred Only'),
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
