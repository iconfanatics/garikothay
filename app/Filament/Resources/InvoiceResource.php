<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'order_number', modifyQueryUsing: fn (Builder $query, string $context) => $context === 'create' ? $query->doesntHave('invoice') : $query)
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->disabled(fn (string $context) => $context !== 'create')
                    ->dehydrated()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if (blank($state)) {
                            $set('billing_information', []);
                            return;
                        }

                        $order = \App\Models\Order::find($state);
                        if ($order) {
                            $billing = $order->billing_address ?: $order->shipping_address ?: [];
                            $name = $billing['full_name'] ?? $order->user?->name ?? '';
                            $phone = $billing['phone'] ?? $order->user?->phone ?? '';
                            $email = $billing['email'] ?? $order->user?->email ?? '';
                            $address = implode(', ', array_filter([
                                $billing['address_line_1'] ?? null,
                                $billing['upazila'] ?? null,
                                $billing['city'] ?? null,
                                $billing['district'] ?? null,
                                $billing['division'] ?? null,
                                $billing['postal_code'] ?? null,
                            ]));

                            $set('billing_information', array_filter([
                                'Name' => $name,
                                'Email' => $email,
                                'Phone' => $phone,
                                'Address' => $address,
                            ]));
                        }
                    }),
                Forms\Components\TextInput::make('invoice_number')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->default(fn () => 'INV-' . date('Ymd') . '-' . strtoupper(str()->random(4)))
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\DateTimePicker::make('invoice_date')
                    ->required()
                    ->default(now())
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\Select::make('status')
                    ->options(\App\Enums\InvoiceStatus::class)
                    ->required()
                    ->default(\App\Enums\InvoiceStatus::Pending)
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\KeyValue::make('billing_information')
                    ->disabled()
                    ->dehydrated()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->paginationPageOptions([50, 100, 250, 'all'])
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Invoice number copied')
                    ->wrap(),
                Tables\Columns\TextColumn::make('invoice_date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Linked Order')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Order number copied'),
                Tables\Columns\TextColumn::make('order.total')
                    ->label('Amount')
                    ->money('BDT')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('invoice_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(\App\Enums\InvoiceStatus::class),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Download PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function (Invoice $record) {
                        if (!$record->order) {
                            \Filament\Notifications\Notification::make()
                                ->title('Error')
                                ->body('The linked order is missing or hard-deleted.')
                                ->danger()
                                ->send();
                            return;
                        }

                        return response()->streamDownload(function () use ($record) {
                            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', ['order' => $record->order]);
                            echo $pdf->output();
                        }, $record->invoice_number . '.pdf');
                    }),
                Tables\Actions\Action::make('view_pdf')
                    ->label('View PDF')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (Invoice $record) => route('invoice.pdf.view', $record))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'view' => Pages\ViewInvoice::route('/{record}'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
