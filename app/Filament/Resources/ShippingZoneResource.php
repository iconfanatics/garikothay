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
                $locations = config('locations', []);

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
                        // For Division Zone
                        Forms\Components\Select::make('coverage_areas_division')
                            ->label('Select Division(s)')
                            ->multiple()
                            ->searchable()
                            ->options(function () use ($locations) {
                                $divs = array_keys($locations);
                                return array_combine($divs, $divs);
                            })
                            ->hidden(fn (Forms\Get $get) => $get('zone_type') !== 'Division')
                            ->required(fn (Forms\Get $get) => $get('zone_type') === 'Division')
                            ->afterStateHydrated(function (Forms\Components\Select $component, ?\App\Models\ShippingZone $record) {
                                if ($record && $record->zone_type === 'Division') $component->state($record->coverage_areas);
                            }),
                            
                        // Helper for District and Upazila
                        Forms\Components\Select::make('division_filter')
                            ->label('1. Select Division')
                            ->options(function () use ($locations) {
                                return array_combine(array_keys($locations), array_keys($locations));
                            })
                            ->live()
                            ->hidden(fn (Forms\Get $get) => !in_array($get('zone_type'), ['District', 'Upazila-Thana'])),
                            
                        // For District Zone
                        Forms\Components\Select::make('coverage_areas_district')
                            ->label('2. Select District(s)')
                            ->multiple()
                            ->searchable()
                            ->options(function (Forms\Get $get) use ($locations) {
                                $div = $get('division_filter');
                                if ($div && isset($locations[$div])) {
                                    $districts = array_keys($locations[$div]);
                                    return array_combine($districts, $districts);
                                }
                                return [];
                            })
                            ->hidden(fn (Forms\Get $get) => $get('zone_type') !== 'District')
                            ->required(fn (Forms\Get $get) => $get('zone_type') === 'District')
                            ->afterStateHydrated(function (Forms\Components\Select $component, ?\App\Models\ShippingZone $record) {
                                if ($record && $record->zone_type === 'District') $component->state($record->coverage_areas);
                            }),

                        // Helper for Upazila
                        Forms\Components\Select::make('district_filter')
                            ->label('2. Select District')
                            ->options(function (Forms\Get $get) use ($locations) {
                                $div = $get('division_filter');
                                if ($div && isset($locations[$div])) {
                                    $districts = array_keys($locations[$div]);
                                    return array_combine($districts, $districts);
                                }
                                return [];
                            })
                            ->live()
                            ->hidden(fn (Forms\Get $get) => $get('zone_type') !== 'Upazila-Thana'),

                        // For Upazila Zone
                        Forms\Components\Select::make('coverage_areas_upazila')
                            ->label('3. Select Upazila(s)')
                            ->multiple()
                            ->searchable()
                            ->options(function (Forms\Get $get) use ($locations) {
                                $div = $get('division_filter');
                                $dist = $get('district_filter');
                                if ($div && $dist && isset($locations[$div][$dist])) {
                                    $upas = $locations[$div][$dist];
                                    return array_combine($upas, $upas);
                                }
                                return [];
                            })
                            ->hidden(fn (Forms\Get $get) => $get('zone_type') !== 'Upazila-Thana')
                            ->required(fn (Forms\Get $get) => $get('zone_type') === 'Upazila-Thana')
                            ->afterStateHydrated(function (Forms\Components\Select $component, ?\App\Models\ShippingZone $record) {
                                if ($record && $record->zone_type === 'Upazila-Thana') $component->state($record->coverage_areas);
                            }),
                            
                        // For Custom Area
                        Forms\Components\TagsInput::make('coverage_areas_tags')
                            ->label('Enter Custom Areas')
                            ->placeholder('Type and press Enter...')
                            ->hidden(fn (Forms\Get $get) => $get('zone_type') !== 'Custom Area')
                            ->required(fn (Forms\Get $get) => $get('zone_type') === 'Custom Area')
                            ->afterStateHydrated(function (Forms\Components\TagsInput $component, ?\App\Models\ShippingZone $record) {
                                if ($record && $record->zone_type === 'Custom Area') $component->state($record->coverage_areas);
                            }),
                            
                        // Hidden field to store actual value via state management
                        Forms\Components\Hidden::make('coverage_areas')
                            ->dehydrated(true)
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
                                    ->live()
                                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                                        $defaults = [
                                            'Standard' => ['charge' => 60, 'time' => '3-5 Business Days'],
                                            'Express' => ['charge' => 120, 'time' => '1-2 Business Days'],
                                            'Pickup' => ['charge' => 0, 'time' => 'Same Day'],
                                            'Same Day' => ['charge' => 150, 'time' => 'Same Day'],
                                        ];
                                        if (isset($defaults[$state])) {
                                            $set('base_charge', $defaults[$state]['charge']);
                                            $set('estimated_delivery_time', $defaults[$state]['time']);
                                        }
                                    })
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
                                    ->options(function(Forms\Get $get) {
                                        $type = $get('shipping_type');
                                        if ($type === 'Same Day') {
                                            return ['Same Day' => 'Same Day'];
                                        }
                                        if ($type === 'Pickup') {
                                            return ['Same Day' => 'Same Day', 'Next Day' => 'Next Day'];
                                        }
                                        return [
                                            'Same Day' => 'Same Day',
                                            'Next Day' => 'Next Day',
                                            '1-2 Business Days' => '1-2 Business Days',
                                            '2-3 Business Days' => '2-3 Business Days',
                                            '3-5 Business Days' => '3-5 Business Days',
                                            '5-7 Business Days' => '5-7 Business Days',
                                        ];
                                    })
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
