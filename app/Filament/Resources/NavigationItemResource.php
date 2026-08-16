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
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('translations', function ($q) use ($search) {
                            $q->where('label', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy(
                            \App\Models\NavigationItemTranslation::select('label')
                                ->whereColumn('navigation_item_translations.navigation_item_id', 'navigation_items.id')
                                ->where('locale', 'en')
                                ->limit(1),
                            $direction
                        );
                    }),
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
                Tables\Filters\Filter::make('search')
                    ->label('Search')
                    ->form([
                        Forms\Components\TextInput::make('query')
                            ->label('Search Label or URL')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['query'] ?? null,
                            fn (Builder $query, $search) => $query->where('url', 'like', "%{$search}%")
                                ->orWhereHas('translations', fn ($q) => $q->where('label', 'like', "%{$search}%"))
                        );
                    }),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive'),
                Tables\Filters\SelectFilter::make('group')
                    ->label('Menu Location')
                    ->options([
                        'top_nav' => 'Top Navigation',
                        'footer_quick_links' => 'Footer Quick Links',
                        'footer_customer_service' => 'Footer Customer Service',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->label('Date Range (Created)')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Created From')
                            ->live(),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Created Until')
                            ->minDate(fn (Forms\Get $get) => $get('created_from')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'] ?? null, fn($q, $d) => $q->whereDate('created_at', '>=', $d))
                            ->when($data['created_until'] ?? null, fn($q, $d) => $q->whereDate('created_at', '<=', $d));
                    }),
                Tables\Filters\Filter::make('updated_at')
                    ->label('Last Updated Range')
                    ->form([
                        Forms\Components\DatePicker::make('updated_from')
                            ->label('Updated From')
                            ->live(),
                        Forms\Components\DatePicker::make('updated_until')
                            ->label('Updated Until')
                            ->minDate(fn (Forms\Get $get) => $get('updated_from')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['updated_from'] ?? null, fn($q, $d) => $q->whereDate('updated_at', '>=', $d))
                            ->when($data['updated_until'] ?? null, fn($q, $d) => $q->whereDate('updated_at', '<=', $d));
                    }),
            ], layout: Tables\Enums\FiltersLayout::Modal)
            ->filtersFormColumns(2)
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
