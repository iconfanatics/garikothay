<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingZoneResource\Pages;
use App\Filament\Resources\ShippingZoneResource\RelationManagers;
use App\Models\ShippingZone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShippingZoneResource extends Resource
{
    protected static ?string $model = ShippingZone::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
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

        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Basic Information')->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),
                            Forms\Components\Select::make('zone_type')
                                ->label('Zone Type')
                                ->options([
                                    'Nationwide' => 'Nationwide',
                                    'Division' => 'Division',
                                    'District' => 'District',
                                    'Upazila-Thana' => 'Upazila-Thana',
                                    'Custom Area' => 'Custom Area',
                                ])
                                ->default('District')
                                ->live()
                                ->required(),
                            Forms\Components\Textarea::make('description')
                                ->label('Description / Admin Note')
                                ->columnSpanFull()
                                ->rows(2),
                        ]),
                    ]),
                    Forms\Components\Section::make('Dynamic Coverage Area')->schema([
                        Forms\Components\TagsInput::make('coverage_areas')
                            ->label(fn(Forms\Get $get) => 'Select ' . $get('zone_type') . 's')
                            ->placeholder(fn(Forms\Get $get) => 'Add ' . $get('zone_type') . '...')
                            ->hidden(fn (Forms\Get $get) => $get('zone_type') === 'Nationwide')
                            ->suggestions(function (Forms\Get $get) use ($locations) {
                                $type = $get('zone_type');
                                if ($type === 'Division') {
                                    return ['Dhaka', 'Chattogram', 'Rajshahi', 'Khulna', 'Barishal', 'Sylhet', 'Rangpur', 'Mymensingh'];
                                }
                                if ($type === 'District') {
                                    $options = [];
                                    foreach ($locations as $division => $districts) {
                                        foreach ($districts as $district) {
                                            $options[] = $district;
                                        }
                                    }
                                    return $options;
                                }
                                return [];
                            })
                            ->required(fn (Forms\Get $get) => $get('zone_type') !== 'Nationwide'),
                    ])->hidden(fn (Forms\Get $get) => $get('zone_type') === 'Nationwide'),
                ])->columnSpan(['lg' => 1]),
                
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Global Settings')->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Zone Active')
                            ->default(true),
                        Forms\Components\Toggle::make('is_cod_enabled')
                            ->label('Cash on Delivery (Zone Wide)')
                            ->helperText('Enable or disable COD for this entire zone.')
                            ->default(true),
                    ]),
                ])->columnSpan(['lg' => 1]),

                Forms\Components\Section::make('Shipping Methods & Charges')->schema([
                    Forms\Components\Repeater::make('shippingMethods')
                        ->relationship('shippingMethods')
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Method Name')
                                    ->placeholder('e.g., Standard Delivery')
                                    ->required(),
                                Forms\Components\Select::make('shipping_type')
                                    ->label('Shipping Type')
                                    ->options([
                                        'Standard' => 'Standard',
                                        'Express' => 'Express',
                                        'Pickup' => 'Pickup',
                                        'Same Day' => 'Same Day',
                                    ])
                                    ->default('Standard')
                                    ->required(),
                            ]),
                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\TextInput::make('base_charge')
                                    ->label('Fixed Delivery Charge')
                                    ->numeric()
                                    ->prefix('৳')
                                    ->required(),
                                Forms\Components\Select::make('estimated_delivery_time')
                                    ->label('Est. Delivery Time')
                                    ->options([
                                        'Same Day' => 'Same Day',
                                        'Next Day' => 'Next Day',
                                        '1-2 Business Days' => '1-2 Business Days',
                                        '2-3 Business Days' => '2-3 Business Days',
                                        '3-5 Business Days' => '3-5 Business Days',
                                        '5-7 Business Days' => '5-7 Business Days',
                                    ])
                                    ->required(),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true)
                                    ->inline(false),
                            ]),
                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\Toggle::make('free_shipping_enabled')
                                    ->label('Enable Free Shipping')
                                    ->live()
                                    ->inline(false),
                                Forms\Components\TextInput::make('free_shipping_threshold')
                                    ->label('Min. Order Amount for Free Shipping')
                                    ->numeric()
                                    ->prefix('৳')
                                    ->hidden(fn (Forms\Get $get) => !$get('free_shipping_enabled')),
                                Forms\Components\Toggle::make('is_cod_enabled')
                                    ->label('Enable Cash on Delivery')
                                    ->helperText('Specific to this method')
                                    ->default(true)
                                    ->inline(false),
                            ]),
                        ])
                        ->columns(1)
                        ->addActionLabel('Add Shipping Method')
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                ])->columnSpanFull(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->paginationPageOptions([50, 100, 250, 'all'])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\BadgeColumn::make('zone_type')
                    ->colors([
                        'primary' => 'Nationwide',
                        'success' => 'Division',
                        'warning' => 'District',
                        'danger' => 'Upazila-Thana',
                    ]),
                Tables\Columns\TextColumn::make('shipping_methods_count')
                    ->label('Methods')
                    ->counts('shippingMethods')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_cod_enabled')
                    ->label('COD')
                    ->boolean(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            // Relation managers removed because we use Repeater now
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShippingZones::route('/'),
            'create' => Pages\CreateShippingZone::route('/create'),
            'edit' => Pages\EditShippingZone::route('/{record}/edit'),
        ];
    }
}
