<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Site Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Page Details')->schema([
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    
                    Forms\Components\Tabs::make('Translations')->tabs([
                        Forms\Components\Tabs\Tab::make('English')->schema([
                            Forms\Components\TextInput::make('translations.en.title')
                                ->label('Title (EN)')
                                ->required()
                                ->maxLength(255)
                                ->afterStateHydrated(function (Forms\Components\TextInput $component, $state, ?Page $record) {
                                    if ($record) {
                                        $component->state($record->getTranslation('title', 'en'));
                                    }
                                }),
                            Forms\Components\RichEditor::make('translations.en.content')
                                ->label('Content (EN)')
                                ->required()
                                ->columnSpanFull()
                                ->afterStateHydrated(function (Forms\Components\RichEditor $component, $state, ?Page $record) {
                                    if ($record) {
                                        $component->state($record->getTranslation('content', 'en'));
                                    }
                                }),
                            Forms\Components\TextInput::make('translations.en.meta_title')
                                ->label('Meta Title (EN)')
                                ->maxLength(255)
                                ->afterStateHydrated(function (Forms\Components\TextInput $component, $state, ?Page $record) {
                                    if ($record) {
                                        $component->state($record->getTranslation('meta_title', 'en'));
                                    }
                                }),
                            Forms\Components\Textarea::make('translations.en.meta_description')
                                ->label('Meta Description (EN)')
                                ->rows(3)
                                ->afterStateHydrated(function (Forms\Components\Textarea $component, $state, ?Page $record) {
                                    if ($record) {
                                        $component->state($record->getTranslation('meta_description', 'en'));
                                    }
                                }),
                        ]),
                        Forms\Components\Tabs\Tab::make('Bengali (বাংলা)')->schema([
                            Forms\Components\TextInput::make('translations.bn.title')
                                ->label('Title (BN)')
                                ->maxLength(255)
                                ->afterStateHydrated(function (Forms\Components\TextInput $component, $state, ?Page $record) {
                                    if ($record) {
                                        $component->state($record->getTranslation('title', 'bn'));
                                    }
                                }),
                            Forms\Components\RichEditor::make('translations.bn.content')
                                ->label('Content (BN)')
                                ->columnSpanFull()
                                ->afterStateHydrated(function (Forms\Components\RichEditor $component, $state, ?Page $record) {
                                    if ($record) {
                                        $component->state($record->getTranslation('content', 'bn'));
                                    }
                                }),
                            Forms\Components\TextInput::make('translations.bn.meta_title')
                                ->label('Meta Title (BN)')
                                ->maxLength(255)
                                ->afterStateHydrated(function (Forms\Components\TextInput $component, $state, ?Page $record) {
                                    if ($record) {
                                        $component->state($record->getTranslation('meta_title', 'bn'));
                                    }
                                }),
                            Forms\Components\Textarea::make('translations.bn.meta_description')
                                ->label('Meta Description (BN)')
                                ->rows(3)
                                ->afterStateHydrated(function (Forms\Components\Textarea $component, $state, ?Page $record) {
                                    if ($record) {
                                        $component->state($record->getTranslation('meta_description', 'bn'));
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
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy(
                            \App\Models\PageTranslation::select('title')
                                ->whereColumn('page_translations.page_id', 'pages.id')
                                ->where('locale', 'en')
                                ->limit(1),
                            $direction
                        );
                    }),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('search')
                    ->label('Search')
                    ->form([
                        Forms\Components\TextInput::make('query')
                            ->label('Search Title or Slug')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['query'] ?? null,
                            fn (Builder $query, $search) => $query->where('slug', 'like', "%{$search}%")
                                ->orWhereHas('translations', fn ($q) => $q->where('title', 'like', "%{$search}%"))
                        );
                    }),
                Tables\Filters\Filter::make('created_at')
                    ->label('Date Range (Created)')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Created From'),
                        Forms\Components\DatePicker::make('created_until')->label('Created Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'] ?? null, fn($q, $d) => $q->whereDate('created_at', '>=', $d))
                            ->when($data['created_until'] ?? null, fn($q, $d) => $q->whereDate('created_at', '<=', $d));
                    }),
                Tables\Filters\Filter::make('updated_at')
                    ->label('Last Updated Range')
                    ->form([
                        Forms\Components\DatePicker::make('updated_from')->label('Updated From'),
                        Forms\Components\DatePicker::make('updated_until')->label('Updated Until'),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
