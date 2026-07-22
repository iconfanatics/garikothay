import os
import re

file_path = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/CategoryResource.php"

with open(file_path, "r") as f:
    content = f.read()

# 1. Update Form
form_start = content.find("public static function form(Form $form): Form")
form_end = content.find("public static function table(Table $table): Table")

if form_start != -1 and form_end != -1:
    new_form = """public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\\Components\\Grid::make(3)->schema([
                Forms\\Components\\Section::make('Category Details')->schema([
                    Forms\\Components\\Grid::make(2)->schema([
                        Forms\\Components\\TextInput::make('translations.en.name')
                            ->label('Name (English)')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, ?string $state, Forms\\Set $set): void {
                                if ($operation === 'create' && $state) {
                                    $set('slug', \\Illuminate\\Support\\Str::slug($state));
                                }
                            })
                            ->disabled(fn ($record) => $record?->is_locked),
                        Forms\\Components\\TextInput::make('translations.bn.name')
                            ->label('Name (বাংলা)')
                            ->maxLength(255)
                            ->disabled(fn ($record) => $record?->is_locked),
                    ]),
                    Forms\\Components\\Textarea::make('translations.en.description')
                        ->label('Description (English)')
                        ->rows(3)
                        ->disabled(fn ($record) => $record?->is_locked),
                    Forms\\Components\\Textarea::make('translations.bn.description')
                        ->label('Description (বাংলা)')
                        ->rows(3)
                        ->disabled(fn ($record) => $record?->is_locked),
                    Forms\\Components\\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(Category::class, 'slug', ignoreRecord: true)
                        ->disabled(fn ($record) => $record?->is_locked),
                    Forms\\Components\\Select::make('parent_id')
                        ->label('Parent Category')
                        ->options(
                            Category::with('translations')
                                ->whereNull('parent_id')
                                ->get()
                                ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->nullable()
                        ->placeholder('None (Top Level)')
                        ->disabled(fn ($record) => $record?->is_locked),
                    Forms\\Components\\Grid::make(2)->schema([
                        Forms\\Components\\TextInput::make('icon')
                            ->label('Icon Class')
                            ->maxLength(100)
                            ->placeholder('heroicon-o-tag')
                            ->disabled(fn ($record) => $record?->is_locked),
                        Forms\\Components\\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->disabled(fn ($record) => $record?->is_locked),
                    ]),
                ])->columnSpan(2),

                Forms\\Components\\Grid::make(1)->schema([
                    Forms\\Components\\Section::make('Publishing & Safety')->schema([
                        Forms\\Components\\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->disabled(fn ($record) => $record?->is_locked),
                        Forms\\Components\\Toggle::make('is_featured')
                            ->label('Featured Category')
                            ->default(false)
                            ->disabled(fn ($record) => $record?->is_locked),
                        Forms\\Components\\Toggle::make('is_locked')
                            ->label('Lock Category (Prevent Edit/Delete)')
                            ->default(false),
                        Forms\\Components\\Select::make('publish_status')
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
                        Forms\\Components\\DateTimePicker::make('published_at')
                            ->label('Publish Date & Time')
                            ->visible(fn (Forms\\Get $get) => in_array($get('publish_status'), ['Scheduled', 'Published']))
                            ->disabled(fn ($record) => $record?->is_locked),
                        Forms\\Components\\DateTimePicker::make('unpublished_at')
                            ->label('Unpublish Date & Time')
                            ->visible(fn (Forms\\Get $get) => in_array($get('publish_status'), ['Scheduled', 'Published', 'Unpublished']))
                            ->disabled(fn ($record) => $record?->is_locked),
                    ]),
                    Forms\\Components\\Section::make('Activity Indicator')->schema([
                        Forms\\Components\\Placeholder::make('created_by')
                            ->label('Created By')
                            ->content(fn ($record) => $record?->createdByAdmin?->name ?? 'System'),
                        Forms\\Components\\Placeholder::make('created_at')
                            ->label('Created At')
                            ->content(fn ($record) => $record?->created_at?->format('d M Y, h:i A') ?? '-'),
                        Forms\\Components\\Placeholder::make('updated_by')
                            ->label('Last Edited By')
                            ->content(fn ($record) => $record?->updatedByAdmin?->name ?? 'System'),
                        Forms\\Components\\Placeholder::make('updated_at')
                            ->label('Last Saved Time')
                            ->content(fn ($record) => $record?->updated_at?->format('d M Y, h:i A') ?? '-'),
                    ]),
                ])->columnSpan(1),
            ]),

            Forms\\Components\\Section::make('Media')->schema([
                Forms\\Components\\Grid::make(3)->schema([
                    Forms\\Components\\FileUpload::make('image')
                        ->label('Thumbnail Image')
                        ->disk('public')
                        ->image()
                        ->directory('categories/thumbnails')
                        ->maxSize(2048)
                        ->disabled(fn ($record) => $record?->is_locked),
                    Forms\\Components\\FileUpload::make('cover_image')
                        ->label('Cover Image')
                        ->disk('public')
                        ->image()
                        ->directory('categories/covers')
                        ->maxSize(4096)
                        ->disabled(fn ($record) => $record?->is_locked),
                    Forms\\Components\\FileUpload::make('banner_image')
                        ->label('Banner Image')
                        ->disk('public')
                        ->image()
                        ->directory('categories/banners')
                        ->maxSize(4096)
                        ->disabled(fn ($record) => $record?->is_locked),
                    Forms\\Components\\FileUpload::make('mobile_banner')
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

    """
    content = content[:form_start] + new_form + content[form_end:]

# 2. Update Table
table_start = content.find("public static function table(Table $table): Table")
table_end = content.find("public static function getPages(): array")

if table_start != -1 and table_end != -1:
    new_table = """public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\\Columns\\TextColumn::make('id')->label('Category ID')->sortable()->searchable(),
                Tables\\Columns\\ImageColumn::make('image')
                    ->label('Thumbnail')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl('/images/placeholder.png'),
                Tables\\Columns\\TextColumn::make('name')
                    ->label('Name')
                    ->formatStateUsing(function ($state, $record): string {
                        $prefix = $record->created_at >= now()->subDays(7) ? '🆕 ' : '';
                        return $prefix . ($record->parent_id ? '↳ ' . $state : $state);
                    })
                    ->searchable(query: function ($query, string $search): void {
                        $query->whereHas('translations', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                    })
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('parent.name')
                    ->label('Parent Category')
                    ->placeholder('— (Top Level)')
                    ->badge()
                    ->color('gray'),
                Tables\\Columns\\BadgeColumn::make('publish_status')
                    ->label('Status')
                    ->colors([
                        'danger' => 'Archived',
                        'warning' => 'Draft',
                        'success' => 'Published',
                        'primary' => 'Scheduled',
                        'secondary' => 'Unpublished',
                    ]),
                Tables\\Columns\\TextColumn::make('products_count')
                    ->label('Total Products')
                    ->counts('products')
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('children_count')
                    ->label('Subcategories')
                    ->counts('children')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'gray'),
                Tables\\Columns\\TextColumn::make('created_at')
                    ->label('Created Date')
                    ->dateTime('d M Y')
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->groups([
                Tables\\Grouping\\Group::make('parent_id')
                    ->label('Parent Category')
                    ->getTitleFromRecordUsing(fn (Category $record) => $record->parent?->name ?? 'Top Level')
                    ->collapsible(),
            ])
            ->filters([
                Tables\\Filters\\TrashedFilter::make(),
                Tables\\Filters\\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only'),
                Tables\\Filters\\Filter::make('root_only')
                    ->label('Top-Level Only')
                    ->query(fn ($query) => $query->whereNull('parent_id')),
                Tables\\Filters\\Filter::make('sub_only')
                    ->label('Subcategories Only')
                    ->query(fn ($query) => $query->whereNotNull('parent_id')),
                Tables\\Filters\\SelectFilter::make('parent_id')
                    ->label('Under Parent')
                    ->options(
                        Category::with('translations')
                            ->whereNull('parent_id')
                            ->get()
                            ->pluck('name', 'id')
                    ),
                Tables\\Filters\\Filter::make('empty_categories')
                    ->label('Empty Categories (No Products)')
                    ->query(fn ($query) => $query->doesntHave('products'))
                    ->toggle(),
            ])
            ->actions([
                Tables\\Actions\\Action::make('preview')
                    ->label('Live Preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Category $record): string => url('/category/' . $record->slug))
                    ->openUrlInNewTab(),
                Tables\\Actions\\EditAction::make(),
                Tables\\Actions\\DeleteAction::make()
                    ->action(function ($record, $action) {
                        if ($record->is_locked) {
                            \\Filament\\Notifications\\Notification::make()->title('Cannot delete a locked category!')->danger()->send();
                            $action->cancel();
                        } else {
                            $record->delete();
                        }
                    }),
                Tables\\Actions\\RestoreAction::make(),
                Tables\\Actions\\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\\Actions\\BulkActionGroup::make([
                    Tables\\Actions\\DeleteBulkAction::make()
                        ->action(function (\\Illuminate\\Database\\Eloquent\\Collection $records, $action) {
                            $unlocked = $records->filter(fn ($r) => !$r->is_locked);
                            $unlocked->each->delete();
                            if ($unlocked->count() < $records->count()) {
                                \\Filament\\Notifications\\Notification::make()->title('Some locked categories were not deleted.')->warning()->send();
                            }
                        }),
                    Tables\\Actions\\RestoreBulkAction::make(),
                    Tables\\Actions\\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    """
    content = content[:table_start] + new_table + content[table_end:]

# Add Builder and SoftDeletingScope imports and function
builder_import = "use Illuminate\\Database\\Eloquent\\Builder;"
scope_import = "use Illuminate\\Database\\Eloquent\\SoftDeletingScope;"

if builder_import not in content:
    content = content.replace("use Filament\\Tables\\Table;", "use Filament\\Tables\\Table;\\n" + builder_import + "\\n" + scope_import)

query_override = """    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}"""
content = content.replace("}\n", query_override + "\n")


with open(file_path, "w") as f:
    f.write(content)

print("Update script generated!")
