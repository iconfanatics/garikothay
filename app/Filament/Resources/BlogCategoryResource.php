<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\BlogCategoryResource\Pages;
use App\Models\BlogCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class BlogCategoryResource extends Resource
{
    protected static ?string $model = BlogCategory::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Blog Categories';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Category Details')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('translations.en.name')
                        ->label('Name (English)')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, ?string $state, Forms\Set $set): void {
                            if ($operation === 'create' && filled($state)) {
                                $set('slug', Str::slug($state));
                            }
                        }),
                    Forms\Components\TextInput::make('translations.bn.name')
                        ->label('Name (বাংলা)')
                        ->maxLength(255),
                ]),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(BlogCategory::class, 'slug', ignoreRecord: true),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
                Forms\Components\FileUpload::make('image')
                    ->label('Featured Image / Icon')
                    ->image()
                    ->directory('blog_categories')
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Category')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas(
                            'translations',
                            fn (Builder $translationQuery): Builder => $translationQuery
                                ->where('name', 'like', "%{$search}%"),
                        );
                    }),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image/Icon')
                    ->circular(false)
                    ->size(40),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('blogs_count')
                    ->label('Posts')
                    ->counts('blogs')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn (BlogCategory $record): bool => $record->blogs()->exists()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogCategories::route('/'),
            'create' => Pages\CreateBlogCategory::route('/create'),
            'edit' => Pages\EditBlogCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('translations');
    }
}
