<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Payment Management';
    protected static ?string $modelLabel = 'Transaction';
    protected static ?string $pluralModelLabel = 'Transactions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Payment Details')->schema([
                    Forms\Components\Select::make('order_id')
                        ->relationship('order', 'order_number')
                        ->searchable()
                        ->required(),
                    Forms\Components\Placeholder::make('customer')
                        ->label('Customer')
                        ->content(fn ($record) => $record?->order?->user?->name ?? $record?->order?->customer_name ?? 'N/A'),
                    Forms\Components\TextInput::make('payment_method')
                        ->label('Payment Gateway')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('transaction_id')
                        ->label('Gateway Transaction ID')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('payment_reference')
                        ->label('Payment Reference')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('amount')
                        ->required()
                        ->numeric()
                        ->prefix('৳'),
                    Forms\Components\TextInput::make('currency')
                        ->required()
                        ->default('BDT')
                        ->maxLength(3),
                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'completed' => 'Completed',
                            'failed' => 'Failed',
                            'refunded' => 'Refunded',
                        ])
                        ->required()
                        ->default('pending'),
                    Forms\Components\DateTimePicker::make('paid_at'),
                ])->columns(2),
                
                Forms\Components\Section::make('Gateway Response')->schema([
                    Forms\Components\TextInput::make('gateway_response_code')
                        ->label('Gateway Response Code')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('gateway_response_message')
                        ->label('Gateway Response Message')
                        ->rows(2)
                        ->columnSpanFull(),
                    Forms\Components\KeyValue::make('gateway_response')
                        ->label('Raw Gateway Data')
                        ->columnSpanFull(),
                ])->columns(2),

                Forms\Components\Section::make('Refund Information')->schema([
                    Forms\Components\TextInput::make('refund_amount')
                        ->label('Refund Amount')
                        ->numeric()
                        ->prefix('৳'),
                    Forms\Components\DateTimePicker::make('refund_date')
                        ->label('Refund Date'),
                    Forms\Components\TextInput::make('refund_transaction_id')
                        ->label('Refund Transaction ID')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('refund_reason')
                        ->label('Refund Reason')
                        ->rows(2)
                        ->columnSpanFull(),
                ])->columns(2),

                Forms\Components\Section::make('Additional Information')->schema([
                    Forms\Components\Textarea::make('remarks')
                        ->label('Remarks / Admin Notes')
                        ->rows(3)
                        ->columnSpanFull(),
                    Forms\Components\Placeholder::make('created_by')
                        ->label('Created By')
                        ->content(fn ($record) => $record?->createdByAdmin?->name ?? 'System'),
                    Forms\Components\Placeholder::make('created_at')
                        ->label('Created At')
                        ->content(fn ($record) => $record?->created_at?->format('d M Y, h:i A') ?? '-'),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Order Number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money(fn ($record) => $record->currency ?? 'BDT')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'failed',
                        'secondary' => 'refunded',
                    ]),
                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('From Date'),
                        Forms\Components\DatePicker::make('created_until')->label('To Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('From: ' . \Carbon\Carbon::parse($data['created_from'])->toFormattedDateString())
                                ->removeField('created_from');
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Until: ' . \Carbon\Carbon::parse($data['created_until'])->toFormattedDateString())
                                ->removeField('created_until');
                        }
                        return $indicators;
                    }),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options(function () {
                        return \App\Models\PaymentGateway::pluck('name', 'slug')->toArray();
                    }),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContent)
            ->defaultPaginationPageOption(30)
            ->paginationPageOptions([10, 30, 50, 100, 'all'])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export_csv')
                    ->label('Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($livewire) {
                        $query = clone $livewire->getFilteredTableQuery();
                        
                        return response()->streamDownload(function () use ($query) {
                            $handle = fopen('php://output', 'w');
                            fputcsv($handle, ['ID', 'Order Number', 'Customer', 'Gateway', 'Transaction ID', 'Payment Reference', 'Amount', 'Currency', 'Status', 'Gateway Response Code', 'Refund Amount', 'Refund Date', 'Refund Transaction ID', 'Refund Reason', 'Remarks', 'Paid At', 'Created By', 'Created At']);
                            
                            foreach ($query->cursor() as $payment) {
                                fputcsv($handle, [
                                    $payment->id,
                                    $payment->order->order_number ?? '',
                                    $payment->order->user->name ?? $payment->order->customer_name ?? 'N/A',
                                    $payment->payment_method,
                                    $payment->transaction_id,
                                    $payment->payment_reference,
                                    $payment->amount,
                                    $payment->currency,
                                    $payment->status,
                                    $payment->gateway_response_code,
                                    $payment->refund_amount,
                                    $payment->refund_date,
                                    $payment->refund_transaction_id,
                                    $payment->refund_reason,
                                    $payment->remarks,
                                    $payment->paid_at,
                                    $payment->createdByAdmin->name ?? 'System',
                                    $payment->created_at,
                                ]);
                            }
                            fclose($handle);
                        }, 'transactions-' . now()->format('Y-m-d') . '.csv');
                    }),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
