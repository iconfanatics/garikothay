<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NavigationItemResource\Pages;
use App\Models\NavigationItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NavigationItemResource extends Resource
{
    protected static ?string $model = NavigationItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $navigationGroup = 'Site Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Item Details')->schema([
                    Forms\Components\Select::make('group')
                        ->options([
                            'top_nav' => 'Top Navigation',
                            'footer_quick_links' => 'Footer Quick Links',
                            'footer_customer_service' => 'Footer Customer Service',
                        ])
                        ->required()
                        ->default('top_nav'),
                    
                    Forms\Components\TextInput::make('url')
                        ->label('URL (e.g. /about or https://example.com)')
                        ->maxLength(255),
                    
                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->required(),
                    
                    Forms\Components\Toggle::make('is_active')
                        ->default(true)
                        ->required(),

                    Forms\Components\Tabs::make('Translations')->tabs([
                        Forms\Components\Tabs\Tab::make('English')->schema([
                            Forms\Components\TextInput::make('translations.en.label')
                                ->label('Label (EN)')
                                ->required()
                                ->maxLength(255)
                                ->afterStateHydrated(function (Forms\Components\TextInput $component, $state, ?NavigationItem $record) {
                                    if ($record) {
                                        $component->state($record->getTranslation('label', 'en'));
                                    }
                                }),
                        ]),
                        Forms\Components\Tabs\Tab::make('Bengali (বাংলা)')->schema([
                            Forms\Components\TextInput::make('translations.bn.label')
                                ->label('Label (BN)')
                                ->maxLength(255)
                                ->afterStateHydrated(function (Forms\Components\TextInput $component, $state, ?NavigationItem $record) {
                                    if ($record) {
                                        $component->state($record->getTranslation('label', 'bn'));
                                    }
                                }),
                        ]),
                    ])->columnSpanFull(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->paginationPageOptions([50, 100, 250, 'all'])
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('group')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('url')
                    ->searchable(),
                Tables\Columns\TextInputColumn::make('sort_order')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'top_nav' => 'Top Navigation',
                        'footer_quick_links' => 'Footer Quick Links',
                        'footer_customer_service' => 'Footer Customer Service',
                    ])
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
            'index' => Pages\ListNavigationItems::route('/'),
            'create' => Pages\CreateNavigationItem::route('/create'),
            'edit' => Pages\EditNavigationItem::route('/{record}/edit'),
        ];
    }
}
