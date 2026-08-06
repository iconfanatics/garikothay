<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogCommentResource\Pages;
use App\Filament\Resources\BlogCommentResource\RelationManagers;
use App\Models\BlogComment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlogCommentResource extends Resource
{
    protected static ?string $model = BlogComment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Comment Details')->schema([
                    Forms\Components\Select::make('blog_id')
                        ->relationship('blog', 'id')
                        ->getOptionLabelFromRecordUsing(fn ($record) => $record->title)
                        ->disabled(),
                    Forms\Components\TextInput::make('name')
                        ->disabled(),
                    Forms\Components\TextInput::make('email')
                        ->disabled(),
                    Forms\Components\Textarea::make('comment')
                        ->columnSpanFull()
                        ->disabled(),
                ])->columns(2),
                Forms\Components\Section::make('Moderation')->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'Pending' => 'Pending',
                            'Approved' => 'Approved',
                            'Rejected' => 'Rejected',
                            'Spam' => 'Spam',
                        ])
                        ->required()
                        ->default('Pending'),
                    Forms\Components\Textarea::make('admin_note')
                        ->label('Admin Note')
                        ->rows(2)
                        ->columnSpanFull(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('blog.title')
                    ->label('Blog Post')
                    ->searchable(query: function ($query, string $search): void {
                        $query->whereHas('blog.translations', fn ($q) => $q->where('title', 'like', "%{$search}%"));
                    })
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('comment')
                    ->limit(50),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Approved',
                        'danger' => fn ($state) => in_array($state, ['Rejected', 'Spam']),
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                        'Spam' => 'Spam',
                    ]),
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
                        ->action(fn ($records) => $records->each->update(['status' => 'Approved'])),
                    Tables\Actions\BulkAction::make('reject')
                        ->label('Reject Selected')
                        ->icon('heroicon-o-x-mark')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->update(['status' => 'Rejected'])),
                    Tables\Actions\BulkAction::make('spam')
                        ->label('Mark as Spam')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['status' => 'Spam'])),
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
            'index' => Pages\ListBlogComments::route('/'),
            'create' => Pages\CreateBlogComment::route('/create'),
            'edit' => Pages\EditBlogComment::route('/{record}/edit'),
        ];
    }
}
