<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Catalog';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(3)->schema([
                Forms\Components\Section::make('Category Details')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('translations.en.name')
                            ->label('Name (English)')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, ?string $state, Forms\Set $set): void {
                                if ($operation === 'create' && $state) {
                                    $set('slug', Str::slug($state));
                                }
                            })
                            ->disabled(fn ($record) => $record?->is_locked),
                        Forms\Components\TextInput::make('translations.bn.name')
                            ->label('Name (বাংলা)')
                            ->maxLength(255)
                            ->disabled(fn ($record) => $record?->is_locked),
                    ]),
                    Forms\Components\Textarea::make('translations.en.description')
                        ->label('Description (English)')
                        ->rows(3)
                        ->disabled(fn ($record) => $record?->is_locked),
                    Forms\Components\Textarea::make('translations.bn.description')
                        ->label('Description (বাংলা)')
                        ->rows(3)
                        ->disabled(fn ($record) => $record?->is_locked),
                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(Category::class, 'slug', ignoreRecord: true)
                        ->disabled(fn ($record) => $record?->is_locked),
                    Forms\Components\Select::make('parent_id')
                        ->label('Parent Category')
                        ->options(
                            Category::whereNull('parent_id')
                                ->with('translations')
                                ->get()
                                ->mapWithKeys(fn($c) => [$c->id => (string) ($c->name ?? 'Category #'.$c->id)])
                        )
                        ->searchable()
                        ->nullable()
                        ->placeholder('None (Top Level)')
                        ->disabled(fn ($record) => $record?->is_locked),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('icon')
                            ->label('Icon Class')
                            ->maxLength(100)
                            ->placeholder('heroicon-o-tag')
                            ->disabled(fn ($record) => $record?->is_locked),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->disabled(fn ($record) => $record?->is_locked),
                    ]),
                ])->columnSpan(2),

                Forms\Components\Grid::make(1)->schema([
                    Forms\Components\Section::make('Publishing & Safety')->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->disabled(fn ($record) => $record?->is_locked),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Category')
                            ->default(false)
                            ->disabled(fn ($record) => $record?->is_locked),
                        Forms\Components\Toggle::make('is_locked')
                            ->label('Lock Category (Prevent Edit/Delete)')
                            ->default(false),
                        Forms\Components\Select::make('publish_status')
                            ->label('Publish Status')
                            ->options([
                                'Draft' => 'Draft',
                                'Scheduled' => 'Scheduled',
                                'Published' => 'Published',
                                'Unpublished' => 'Unpublished',
                                'Archived' => 'Archived',
                            ])
                            ->default('Draft')
                            ->live()
                            ->disabled(fn ($record) => $record?->is_locked),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Publish Date & Time')
                            ->visible(fn (Forms\Get $get) => in_array($get('publish_status'), ['Scheduled', 'Published']))
                            ->disabled(fn ($record) => $record?->is_locked),
                        Forms\Components\DateTimePicker::make('unpublished_at')
                            ->label('Unpublish Date & Time')
                            ->visible(fn (Forms\Get $get) => in_array($get('publish_status'), ['Scheduled', 'Published', 'Unpublished']))
                            ->disabled(fn ($record) => $record?->is_locked),
                    ]),
                    Forms\Components\Section::make('Activity Indicator')->schema([
                        Forms\Components\Placeholder::make('created_by')
                            ->label('Created By')
                            ->content(fn ($record) => $record?->createdByAdmin?->name ?? 'System'),
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created At')
                            ->content(fn ($record) => $record?->created_at?->format('d M Y, h:i A') ?? '-'),
                        Forms\Components\Placeholder::make('updated_by')
                            ->label('Last Edited By')
                            ->content(fn ($record) => $record?->updatedByAdmin?->name ?? 'System'),
                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last Saved Time')
                            ->content(fn ($record) => $record?->updated_at?->format('d M Y, h:i A') ?? '-'),
                    ]),
                ])->columnSpan(1),
            ]),

            Forms\Components\Section::make('Media')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Thumbnail Image')
                        ->disk('public')
                        ->image()
                        ->directory('categories/thumbnails')
                        ->maxSize(2048)
                        ->disabled(fn ($record) => $record?->is_locked),
                    Forms\Components\FileUpload::make('cover_image')
                        ->label('Cover Image')
                        ->disk('public')
                        ->image()
                        ->directory('categories/covers')
                        ->maxSize(4096)
                        ->disabled(fn ($record) => $record?->is_locked),
                    Forms\Components\FileUpload::make('banner_image')
                        ->label('Banner Image')
                        ->disk('public')
                        ->image()
                        ->directory('categories/banners')
                        ->maxSize(4096)
                        ->disabled(fn ($record) => $record?->is_locked),
                    Forms\Components\FileUpload::make('mobile_banner')
                        ->label('Mobile Banner')
                        ->disk('public')
                        ->image()
                        ->directory('categories/mobile-banners')
                        ->maxSize(4096)
                        ->disabled(fn ($record) => $record?->is_locked),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Category ID')->sortable()->searchable(),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Thumbnail')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl('/images/placeholder.png'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->formatStateUsing(function ($state, $record): string {
                        $prefix = $record->created_at >= now()->subDays(7) ? '🆕 ' : '';
                        return $prefix . ($record->parent_id ? '↳ ' . $state : $state);
                    })
                    ->searchable(query: function ($query, string $search): void {
                        $query->whereHas('translations', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent Category')
                    ->placeholder('— (Top Level)')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('publish_status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'danger' => 'Archived',
                        'warning' => 'Draft',
                        'success' => 'Published',
                        'primary' => 'Scheduled',
                        'secondary' => 'Unpublished',
                    ]),
                Tables\Columns\TextColumn::make('products_count')
                    ->label('Total Products')
                    ->counts('products')
                    ->sortable(),
                Tables\Columns\TextColumn::make('children_count')
                    ->label('Subcategories')
                    ->counts('children')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created Date')
                    ->dateTime('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->groups([
                Tables\Grouping\Group::make('parent_id')
                    ->label('Parent Category')
                    ->getTitleFromRecordUsing(fn (Category $record) => $record->parent?->name ?? 'Top Level')
                    ->collapsible(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only'),
                Tables\Filters\SelectFilter::make('level')
                    ->label('Category Level')
                    ->options([
                        'root' => 'Top-Level Only',
                        'sub' => 'Subcategories Only',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['value'] === 'root') {
                            return $query->whereNull('parent_id');
                        }
                        if ($data['value'] === 'sub') {
                            return $query->whereNotNull('parent_id');
                        }
                        return $query;
                    }),
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Under Parent')
                    ->options(
                        Category::with('translations')
                            ->whereNull('parent_id')
                            ->get()
                            ->mapWithKeys(fn($c) => [$c->id => (string) ($c->name ?? 'Category #'.$c->id)])
                    ),
                Tables\Filters\Filter::make('empty_categories')
                    ->label('Empty Categories (No Products)')
                    ->query(fn ($query) => $query->doesntHave('products'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->label('Live Preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Category $record): string => url('/shop?category=' . $record->slug))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(function ($record, $action) {
                        if ($record->is_locked) {
                            \Filament\Notifications\Notification::make()->title('Cannot delete a locked category!')->danger()->send();
                            $action->cancel();
                        } else {
                            $record->delete();
                        }
                    }),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, $action) {
                            $unlocked = $records->filter(fn ($r) => !$r->is_locked);
                            $unlocked->each->delete();
                            if ($unlocked->count() < $records->count()) {
                                \Filament\Notifications\Notification::make()->title('Some locked categories were not deleted.')->warning()->send();
                            }
                        }),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
