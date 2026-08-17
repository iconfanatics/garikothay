<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingMethodResource\Pages;
use App\Filament\Resources\ShippingMethodResource\RelationManagers;
use App\Models\ShippingMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShippingMethodResource extends Resource
{
    protected static ?string $model = ShippingMethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Method Details')->schema([
                    Forms\Components\Select::make('shipping_zone_id')
                        ->relationship('shippingZone', 'name')
                        ->label('Available Shipping Zone')
                        ->required()
                        ->searchable()
                        ->preload(),
                    Forms\Components\TextInput::make('name')
                        ->label('Method Name (e.g. Standard, Express)')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('shipping_type')
                        ->label('Shipping Type')
                        ->options([
                            'Home Delivery' => 'Home Delivery',
                            'Pickup Point' => 'Pickup Point',
                            'Courier Service' => 'Courier Service',
                        ])
                        ->nullable(),
                    Forms\Components\TextInput::make('estimated_delivery_time')
                        ->label('Estimated Delivery Time')
                        ->placeholder('e.g. 2-3 Days')
                        ->maxLength(255),
                ])->columns(2),
                Forms\Components\Section::make('Pricing Rules')->schema([
                    Forms\Components\TextInput::make('base_charge')
                        ->label('Shipping Charge (BDT)')
                        ->numeric()
                        ->default(0.00)
                        ->required(),
                    Forms\Components\TextInput::make('free_shipping_threshold')
                        ->label('Free Shipping Threshold (Order Amount)')
                        ->helperText('If order total is above this amount, shipping will be free. Leave blank for no free shipping.')
                        ->numeric()
                        ->nullable(),
                ])->columns(2),
                Forms\Components\Section::make('Status')->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('shippingZone.name')
                    ->label('Available Shipping Zone')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Method Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shipping_type')
                    ->label('Shipping Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('base_charge')
                    ->label('Shipping Charge')
                    ->money('BDT')
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimated_delivery_time')
                    ->label('Estimated Delivery Time')
                    ->sortable(),
                Tables\Columns\TextColumn::make('free_shipping_threshold')
                    ->label('Free Shipping Over')
                    ->money('BDT')
                    ->placeholder('N/A')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('shipping_zone_id')
            ->groups([
                'shippingZone.name',
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('shipping_zone_id')
                    ->relationship('shippingZone', 'name')
                    ->label('Zone'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShippingMethods::route('/'),
            'create' => Pages\CreateShippingMethod::route('/create'),
            'edit' => Pages\EditShippingMethod::route('/{record}/edit'),
        ];
    }
}
