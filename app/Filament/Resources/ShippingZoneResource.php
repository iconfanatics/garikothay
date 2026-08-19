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
                        Forms\Components\Select::make('division_filter')
                            ->label('Filter by Division')
                            ->options(function () use ($locations) {
                                return array_combine(array_keys($locations), array_keys($locations));
                            })
                            ->live()
                            ->dehydrated(false)
                            ->hidden(fn (Forms\Get $get) => !in_array($get('zone_type'), ['District', 'Upazila-Thana'])),
                            
                        Forms\Components\Select::make('district_filter')
                            ->label('Filter by District')
                            ->options(function (Forms\Get $get) use ($locations) {
                                $div = $get('division_filter');
                                if ($div && isset($locations[$div])) {
                                    $districts = array_keys($locations[$div]);
                                    return array_combine($districts, $districts);
                                }
                                return [];
                            })
                            ->live()
                            ->dehydrated(false)
                            ->hidden(fn (Forms\Get $get) => $get('zone_type') !== 'Upazila-Thana'),

                        Forms\Components\Select::make('coverage_areas_select')
                            ->label(fn(Forms\Get $get) => 'Select ' . $get('zone_type') . 's')
                            ->multiple()
                            ->searchable()
                            ->hidden(fn (Forms\Get $get) => !in_array($get('zone_type'), ['Division', 'District', 'Upazila-Thana']))
                            ->options(function (Forms\Get $get, Forms\Components\Select $component) use ($locations) {
                                $type = $get('zone_type');
                                $div = $get('division_filter');
                                $dist = $get('district_filter');
                                
                                $options = [];
                                
                                if ($type === 'Division') {
                                    $divs = array_keys($locations);
                                    $options = array_combine($divs, $divs);
                                } elseif ($type === 'District') {
                                    if ($div && isset($locations[$div])) {
                                        foreach (array_keys($locations[$div]) as $d) {
                                            $options[$d] = $d . ' (' . $div . ')';
                                        }
                                    } else {
                                        foreach ($locations as $dv => $districts) {
                                            foreach (array_keys($districts) as $d) {
                                                $options[$d] = $d . ' (' . $dv . ')';
                                            }
                                        }
                                    }
                                } elseif ($type === 'Upazila-Thana') {
                                    if ($div && $dist && isset($locations[$div][$dist])) {
                                        foreach ($locations[$div][$dist] as $upa) {
                                            $options[$upa] = $upa . ' (' . $dist . ')';
                                        }
                                    } elseif ($div && isset($locations[$div])) {
                                        foreach ($locations[$div] as $d => $upas) {
                                            foreach ($upas as $upa) {
                                                $options[$upa] = $upa . ' (' . $d . ')';
                                            }
                                        }
                                    } else {
                                        foreach ($locations as $dv => $districts) {
                                            foreach ($districts as $d => $upas) {
                                                foreach ($upas as $upa) {
                                                    $options[$upa] = $upa . ' (' . $d . ')';
                                                }
                                            }
                                        }
                                    }
                                }
                                
                                // Ensure currently selected values are always in the options array
                                $state = $component->getState();
                                if (is_array($state)) {
                                    foreach ($state as $val) {
                                        if (!isset($options[$val])) {
                                            $options[$val] = $val;
                                        }
                                    }
                                }
                                
                                return $options;
                            })
                            ->required(fn (Forms\Get $get) => in_array($get('zone_type'), ['Division', 'District', 'Upazila-Thana']))
                            ->afterStateHydrated(function (Forms\Components\Select $component, ?\App\Models\ShippingZone $record) {
                                if ($record && in_array($record->zone_type, ['Division', 'District', 'Upazila-Thana'])) {
                                    $component->state($record->coverage_areas);
                                }
                            })
                            ->dehydrated(false),
                            
                        Forms\Components\TagsInput::make('coverage_areas_tags')
                            ->label('Enter Custom Areas')
                            ->placeholder('Type and press Enter...')
                            ->hidden(fn (Forms\Get $get) => $get('zone_type') !== 'Custom Area')
                            ->required(fn (Forms\Get $get) => $get('zone_type') === 'Custom Area')
                            ->afterStateHydrated(function (Forms\Components\TagsInput $component, ?\App\Models\ShippingZone $record) {
                                if ($record && $record->zone_type === 'Custom Area') {
                                    $component->state($record->coverage_areas);
                                }
                            })
                            ->dehydrated(false),
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
