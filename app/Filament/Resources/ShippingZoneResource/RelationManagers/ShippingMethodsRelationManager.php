<?php

namespace App\Filament\Resources\ShippingZoneResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShippingMethodsRelationManager extends RelationManager
{
    protected static string $relationship = 'shippingMethods';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Method Name (e.g. Standard, Express)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('base_charge')
                    ->label('Base Charge (BDT)')
                    ->numeric()
                    ->default(0.00)
                    ->required(),
                Forms\Components\TextInput::make('free_shipping_threshold')
                    ->label('Free Shipping Threshold')
                    ->helperText('If order total is above this amount, shipping will be free. Leave blank for no free shipping.')
                    ->numeric()
                    ->nullable(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Method')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('base_charge')
                    ->label('Base Charge')
                    ->money('BDT')
                    ->sortable(),
                Tables\Columns\TextColumn::make('free_shipping_threshold')
                    ->label('Free Shipping Over')
                    ->money('BDT')
                    ->placeholder('N/A')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
