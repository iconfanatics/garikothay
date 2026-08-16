<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use App\Models\BlogCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;
    protected static ?string $recordTitleAttribute = 'slug';

    protected static ?string $navigationIcon = "heroicon-o-document-text";
    protected static ?string $navigationGroup = "Content";
    protected static ?string $navigationLabel = "Blogs";
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make("Blog Content")->schema([
                Forms\Components\Tabs::make("Translations")->tabs([
                    Forms\Components\Tabs\Tab::make("English")->schema([
                        Forms\Components\TextInput::make(
                            "translations.en.title",
                        )
                            ->label("Title (English)")
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (
                                string $operation,
                                ?string $state,
                                Forms\Set $set,
                            ): void {
                                if ($operation === "create" && $state) {
                                    $set("slug", Str::slug($state));
                                }
                            }),
                        Forms\Components\Textarea::make(
                            "translations.en.excerpt",
                        )
                            ->label("Excerpt (English)")
                            ->rows(2)
                            ->maxLength(500),
                        Forms\Components\RichEditor::make(
                            "translations.en.content",
                        )
                            ->label("Content (English)")
                            ->required()
                            ->fileAttachmentsDisk("public")
                            ->fileAttachmentsDirectory("blog/content")
                            ->fileAttachmentsVisibility("public")
                            ->columnSpanFull(),
                    ]),
                    Forms\Components\Tabs\Tab::make("বাংলা")->schema([
                        Forms\Components\TextInput::make(
                            "translations.bn.title",
                        )
                            ->label("শিরোনাম (বাংলা)")
                            ->maxLength(255),
                        Forms\Components\Textarea::make(
                            "translations.bn.excerpt",
                        )
                            ->label("সারসংক্ষেপ (বাংলা)")
                            ->rows(2)
                            ->maxLength(500),
                        Forms\Components\RichEditor::make(
                            "translations.bn.content",
                        )
                            ->label("বিষয়বস্তু (বাংলা)")
                            ->fileAttachmentsDisk("public")
                            ->fileAttachmentsDirectory("blog/content")
                            ->fileAttachmentsVisibility("public")
                            ->columnSpanFull(),
                    ]),
                ]),
            ]),

                        Forms\Components\Section::make('SEO & Tags')->schema([
                Forms\Components\TagsInput::make('tags')
                    ->label('Tags')
                    ->separator(',')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('seo_title')
                    ->label('SEO Title')
                    ->maxLength(255),
                Forms\Components\Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->rows(3)
                    ->columnSpanFull(),
            ]),
            Forms\Components\Section::make("Publishing Details")->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make("slug")
                        ->label("Slug")
                        ->required()
                        ->maxLength(255)
                        ->unique(Blog::class, "slug", ignoreRecord: true),
                    Forms\Components\Select::make("blog_category_id")
                        ->label("Category")
                        ->options(fn (): array =>
                            BlogCategory::with("translations")
                                ->get()
                                ->pluck("name", "id")
                                ->all()
                        )
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make("name_en")
                                ->label("Name (English)")
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make("name_bn")
                                ->label("Name (বাংলা)")
                                ->maxLength(255),
                        ])
                        ->createOptionUsing(function (array $data): int {
                            $baseSlug = Str::slug($data["name_en"]);
                            $slug = $baseSlug;
                            $suffix = 2;

                            while (BlogCategory::where("slug", $slug)->exists()) {
                                $slug = "{$baseSlug}-{$suffix}";
                                $suffix++;
                            }

                            $category = BlogCategory::create(["slug" => $slug]);
                            $category->setTranslation("en", ["name" => $data["name_en"]]);

                            if (filled($data["name_bn"] ?? null)) {
                                $category->setTranslation("bn", ["name" => $data["name_bn"]]);
                            }

                            return $category->id;
                        })
                        ->required(),
                ]),
                Forms\Components\FileUpload::make("featured_image")
                    ->label("Featured Image")
                    ->helperText("Recommended: 1600 x 900 px. JPG, PNG or WebP, maximum 4 MB.")
                    ->disk("public")
                    ->image()
                    ->imageEditor()
                    ->acceptedFileTypes(["image/jpeg", "image/png", "image/webp"])
                    ->directory("blog")
                    ->visibility("public")
                    ->maxSize(4096)
                    ->imageResizeMode("contain")
                    ->imageResizeTargetHeight("900")
                    ->imagePreviewHeight("150")
                    ->columnSpanFull(),
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make("image_alt_text")
                        ->label("Image Alt Text")
                        ->maxLength(255),
                    Forms\Components\TextInput::make("image_caption")
                        ->label("Image Caption")
                        ->maxLength(255),
                ]),
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Toggle::make("is_published")
                        ->label("Published")
                        ->default(false)
                        ->live(),
                    Forms\Components\DateTimePicker::make("published_at")
                        ->label("Publish At")
                        ->nullable()
                        ->rule(fn (string $context) => $context === 'create' ? 'after_or_equal:today' : null)
                        ->visible(
                            fn(Forms\Get $get): bool => (bool) $get(
                                "is_published",
                            ),
                        ),
                    Forms\Components\Select::make("author_id")
                        ->label("Author")
                        ->relationship("author", "name")
                        ->searchable()
                        ->preload()
                        ->default(fn () => \Filament\Facades\Filament::auth()->id())
                        ->required(),
                    Forms\Components\Toggle::make("is_featured")
                        ->label("Featured Blog")
                        ->default(false),
                    Forms\Components\TextInput::make("reading_time_minutes")
                        ->label("Reading Time (Minutes)")
                        ->disabled()
                        ->dehydrated(false)
                        ->helperText("Auto-calculated on save."),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->paginationPageOptions([50, 100, 250, 'all'])
            ->columns([
                Tables\Columns\TextColumn::make("blog_code")
                    ->label("Blog ID")
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make("featured_image")
                    ->label("Image")
                    ->disk("public")
                    ->height(56)
                    ->defaultImageUrl(asset("images/placeholder.png")),
                Tables\Columns\TextColumn::make("title")
                    ->label("Title")
                    ->searchable(
                        query: function ($query, string $search): void {
                            $query->whereHas(
                                "translations",
                                fn($q) => $q->where(
                                    "title",
                                    "like",
                                    "%{$search}%",
                                ),
                            );
                        },
                    )
                    ->limit(50)
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy(
                            \App\Models\BlogTranslation::select('title')
                                ->whereColumn('blog_translations.blog_id', 'blogs.id')
                                ->where('locale', 'en')
                                ->limit(1),
                            $direction
                        );
                    }),
                Tables\Columns\TextColumn::make("category.name")
                    ->label("Category")
                    ->default("—"),
                Tables\Columns\TextColumn::make("slug")
                    ->label("Slug")
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ToggleColumn::make("is_published")->label(
                    "Published",
                ),
                Tables\Columns\TextColumn::make("published_at")
                    ->label("Published At")
                    ->dateTime("d M Y")
                    ->placeholder("—")
                    ->sortable(),
                Tables\Columns\TextColumn::make("created_at")
                    ->label("Created")
                    ->dateTime("d M Y")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort("created_at", "desc")
            ->filters([
                Tables\Filters\TernaryFilter::make("is_published")
                    ->label("Published Status")
                    ->trueLabel("Published")
                    ->falseLabel("Draft"),
                Tables\Filters\SelectFilter::make("blog_category_id")
                    ->label("Category")
                    ->options(
                        BlogCategory::with("translations")
                            ->get()
                            ->pluck("name", "id"),
                    ),
                Tables\Filters\SelectFilter::make("author_id")
                    ->label("Author")
                    ->relationship("author", "name")
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make("is_featured")
                    ->label("Featured Blog")
                    ->trueLabel("Featured Only")
                    ->falseLabel("Non-Featured Only"),
                Tables\Filters\Filter::make('published_at')
                    ->form([
                        Forms\Components\DatePicker::make('published_from'),
                        Forms\Components\DatePicker::make('published_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['published_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '>=', $date),
                            )
                            ->when(
                                $data['published_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make("publish")
                        ->label("Publish Selected")
                        ->icon("heroicon-o-eye")
                        ->color("success")
                        ->action(
                            fn($records) => $records->each->update([
                                "is_published" => true,
                            ]),
                        ),
                    Tables\Actions\BulkAction::make("unpublish")
                        ->label("Unpublish Selected")
                        ->icon("heroicon-o-eye-slash")
                        ->color("warning")
                        ->action(
                            fn($records) => $records->each->update([
                                "is_published" => false,
                            ]),
                        ),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListBlogs::route("/"),
            "create" => Pages\CreateBlog::route("/create"),
            "edit" => Pages\EditBlog::route("/{record}/edit"),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(["category", "translations"]);
    }
}
