<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CartResource\Pages;
use App\Models\Cart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CartResource extends Resource
{
    protected static ?string $model = Cart::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return 'Abandoned Cart';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Abandoned Carts';
    }

    public static function getNavigationLabel(): string
    {
        return 'Abandoned Carts';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('items')
            ->where('updated_at', '<=', now()->subHours(2))
            ->with(['user', 'items']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->paginationPageOptions([50, 100, 250, 'all'])
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->default('Guest')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->default('N/A')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.phone')
                    ->label('Phone')
                    ->default('N/A')
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_count')
                    ->label('Items')
                    ->getStateUsing(fn (Cart $record): int => $record->item_count),
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Cart Value')
                    ->money('BDT')
                    ->getStateUsing(fn (Cart $record): float => $record->subtotal),
                Tables\Columns\BadgeColumn::make('recovery_status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Recovered',
                        'danger' => 'Expired',
                    ]),
                Tables\Columns\IconColumn::make('is_reminder_sent')
                    ->label('Reminder')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\TextColumn::make('reminder_sent_at')
                    ->label('Reminder Sent At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Active')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('recovery_status')
                    ->label('Recovery Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Recovered' => 'Recovered',
                        'Expired' => 'Expired',
                    ]),
                Tables\Filters\Filter::make('updated_at')
                    ->label('Abandoned Date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('updated_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('updated_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('customer_profile')
                    ->label('Customer')
                    ->icon('heroicon-o-user')
                    ->color('secondary')
                    ->url(fn (Cart $record) => $record->user_id ? route('filament.admin.resources.customers.edit', $record->user_id) : null)
                    ->hidden(fn (Cart $record) => !$record->user_id),
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Abandoned Cart Details')
                    ->modalContent(fn (Cart $record) => view('filament.widgets.cart-details-modal', ['cart' => $record])),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('mark_recovered')
                        ->label('Mark Recovered')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn (Cart $record) => $record->update(['recovery_status' => 'Recovered'])),
                    Tables\Actions\Action::make('mark_expired')
                        ->label('Mark Expired')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->action(fn (Cart $record) => $record->update(['recovery_status' => 'Expired'])),
                    Tables\Actions\Action::make('send_reminder')
                        ->label('Send Reminder')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('primary')
                        ->action(fn (Cart $record) => $record->update([
                            'is_reminder_sent' => true,
                            'reminder_sent_at' => now(),
                        ]))
                        ->requiresConfirmation(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No abandoned carts found')
            ->emptyStateDescription('Great! All carts are either active or converted.');
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
            'index' => Pages\ListCarts::route('/'),
        ];
    }
}
