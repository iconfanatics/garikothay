<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Product;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Catalog';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Review Details')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('user.name')
                        ->label('Customer')
                        ->disabled(),
                    Forms\Components\TextInput::make('product.name')
                        ->label('Product')
                        ->disabled(),
                ]),
                Forms\Components\TextInput::make('rating')
                    ->label('Rating')
                    ->disabled(),
                Forms\Components\TextInput::make('title')
                    ->label('Review Title')
                    ->disabled(),
                Forms\Components\Textarea::make('comment')
                    ->label('Comment')
                    ->rows(4)
                    ->disabled(),
            ]),

            Forms\Components\Section::make('Moderation')->schema([
                Forms\Components\Toggle::make('is_approved')
                    ->label('Approved')
                    ->helperText('Approved reviews are visible on the product page.'),
                Forms\Components\Textarea::make('admin_reply')
                    ->label('Admin Reply')
                    ->rows(3)
                    ->placeholder('Optional: Add a public reply to this review...'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable(query: function ($query, string $value): void {
                        $query->whereHas('product.translations', fn ($q) => $q->where('name', 'like', "%{$value}%"));
                    })
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn (int $state): string => str_repeat('★', $state) . str_repeat('☆', 5 - $state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->limit(40)
                    ->default('—'),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Comment')
                    ->limit(60)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ToggleColumn::make('is_approved')
                    ->label('Approved'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Approval Status')
                    ->trueLabel('Approved')
                    ->falseLabel('Pending'),
                Tables\Filters\SelectFilter::make('rating')
                    ->label('Rating')
                    ->options([
                        '5' => '5 Stars',
                        '4' => '4 Stars',
                        '3' => '3 Stars',
                        '2' => '2 Stars',
                        '1' => '1 Star',
                    ]),
                Tables\Filters\SelectFilter::make('product_id')
                    ->label('Product')
                    ->options(
                        Product::with('translations')
                            ->get()
                            ->pluck('name', 'id')
                    )
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_approved' => true])),
                    Tables\Actions\BulkAction::make('reject')
                        ->label('Reject Selected')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_approved' => false])),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'edit'  => Pages\EditReview::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user', 'product.translations']);
    }
}
