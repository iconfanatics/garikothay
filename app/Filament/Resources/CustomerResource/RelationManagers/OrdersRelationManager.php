<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Components\Tab;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user_id')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Order Date')->dateTime('d M Y, h:i A')->sortable(),
                Tables\Columns\TextColumn::make('order_number')->label('Order #')->searchable()->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (\App\Enums\OrderStatus $state): string => $state->label())
                    ->color(fn (\App\Enums\OrderStatus $state): string => $state->color()),
                Tables\Columns\TextColumn::make('total')->label('Total')->money('BDT')->sortable(),
                Tables\Columns\TextColumn::make('items_count')->counts('items')->label('Total Items'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->url(fn ($livewire) => route('filament.admin.resources.orders.create', ['user_id' => $livewire->ownerRecord->id])),
            ])
            ->actions([
                Tables\Actions\Action::make('view_order')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn (\App\Models\Order $record): string => route('filament.admin.resources.orders.edit', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
            ]);
    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Orders'),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', \App\Enums\OrderStatus::Pending->value)),
            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', \App\Enums\OrderStatus::Delivered->value)),
            'returned' => Tab::make('Returns')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', \App\Enums\OrderStatus::Returned->value)),
            'refunded' => Tab::make('Refunds')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', \App\Enums\OrderStatus::Refunded->value)),
        ];
    }
}
