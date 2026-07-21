import os

file_path = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/OrderResource.php"

with open(file_path, "r") as f:
    content = f.read()

start_marker = "public static function table(Table $table): Table"
end_marker = "public static function getPages(): array"

start_idx = content.find(start_marker)
end_idx = content.find(end_marker)

if start_idx == -1 or end_idx == -1:
    print("Could not find markers")
    exit(1)

table_replacement = """public static function table(Table $table): Table
    {
        return $table
            ->recordClasses(fn (Order $record) => $record->status === OrderStatus::Pending ? 'bg-primary-50/50 dark:bg-primary-900/10 border-l-4 border-primary-500 font-semibold' : null)
            ->columns([
                Tables\\Columns\\TextColumn::make('created_at')
                    ->label('Order Date & Time')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('assignedStaff.name')
                    ->label('Assigned Staff')
                    ->sortable()
                    ->searchable()
                    ->default('Unassigned'),
                Tables\\Columns\\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\\Columns\\TextColumn::make('order_source')
                    ->label('Order Source')
                    ->searchable()
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('customer_type')
                    ->label('Customer Type')
                    ->searchable()
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('payment.paid_at')
                    ->label('Payment Date')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\\Columns\\IconColumn::make('is_fraud')
                    ->label('Fraud Flag')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->trueColor('danger')
                    ->falseIcon('heroicon-o-check-circle')
                    ->falseColor('success')
                    ->toggleable(),
                Tables\\Columns\\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Total Items')
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->formatStateUsing(fn ($state) => $state instanceof \\App\\Enums\\PaymentMethod ? $state->label() : (is_string($state) ? \\App\\Enums\\PaymentMethod::tryFrom($state)?->label() ?? strtoupper($state) : 'N/A'))
                    ->searchable(),
                Tables\\Columns\\TextColumn::make('delivery_method')
                    ->label('Delivery Method')
                    ->searchable()
                    ->sortable(),
                Tables\\Columns\\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (OrderStatus $state): string => $state->label())
                    ->color(fn (OrderStatus $state): string => $state->color()),
                Tables\\Columns\\TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\\Columns\\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\\Columns\\TextColumn::make('total')
                    ->label('Total')
                    ->money('BDT')
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('tracking_number')
                    ->label('Tracking #')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\\Columns\\TextColumn::make('invoice.invoice_number')
                    ->label('Invoice #')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\\Columns\\TextColumn::make('shipping_phone')
                    ->label('Shipping Phone')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('shipping_address->phone', 'like', "%{$search}%")
                                     ->orWhereHas('user', fn($q) => $q->where('phone', 'like', "%{$search}%"));
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\\Filters\\SelectFilter::make('status')
                    ->label('Order Status')
                    ->options(OrderStatus::options()),
                Tables\\Filters\\SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options(PaymentStatus::options()),
                Tables\\Filters\\SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'cod' => 'Cash on Delivery',
                        'sslcommerz' => 'SSLCommerz',
                        'stripe' => 'Stripe',
                        'bkash' => 'bKash',
                    ]),
                Tables\\Filters\\SelectFilter::make('delivery_method')
                    ->label('Delivery Method')
                    ->options([
                        'Pathao' => 'Pathao',
                        'RedX' => 'RedX',
                        'Steadfast' => 'Steadfast',
                        'SA Paribahan' => 'SA Paribahan',
                        'Sundarban' => 'Sundarban',
                        'Own Delivery' => 'Own Delivery',
                    ]),
                Tables\\Filters\\Filter::make('created_at')
                    ->form([
                        Forms\\Components\\DatePicker::make('from')->label('From Date'),
                        Forms\\Components\\DatePicker::make('until')->label('To Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
                Tables\\Filters\\Filter::make('total')
                    ->form([
                        Forms\\Components\\TextInput::make('min')->label('Min Amount')->numeric(),
                        Forms\\Components\\TextInput::make('max')->label('Max Amount')->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['min'], fn ($q, $min) => $q->where('total', '>=', $min))
                            ->when($data['max'], fn ($q, $max) => $q->where('total', '<=', $max));
                    }),
                Tables\\Filters\\SelectFilter::make('assigned_staff_id')
                    ->label('Assigned Staff')
                    ->relationship('assignedStaff', 'name'),
            ])
            ->actions([
                Tables\\Actions\\EditAction::make()->label('Manage Order'),
                Tables\\Actions\\Action::make('download_invoice')
                    ->label('Download Invoice')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function (Order $record) {
                        $pdf = \\Barryvdh\\DomPDF\\Facade\\Pdf::loadView('pdf.invoice', ['order' => $record]);
                        return response()->streamDownload(fn () => print($pdf->output()), 'invoice-' . $record->order_number . '.pdf');
                    }),
                Tables\\Actions\\Action::make('download_vendor_slip')
                    ->label('Vendor Slip')
                    ->icon('heroicon-o-truck')
                    ->color('warning')
                    ->action(function (Order $record) {
                        $pdf = \\Barryvdh\\DomPDF\\Facade\\Pdf::loadView('pdf.vendor-slip', ['order' => $record]);
                        return response()->streamDownload(fn () => print($pdf->output()), 'vendor-slip-' . $record->order_number . '.pdf');
                    }),
            ])
            ->bulkActions([
                Tables\\Actions\\BulkActionGroup::make([
                    Tables\\Actions\\BulkAction::make('mark_confirmed')
                        ->label('Mark as Confirmed')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['status' => OrderStatus::Confirmed]))
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    """

new_content = content[:start_idx] + table_replacement + content[end_idx:]

with open(file_path, "w") as f:
    f.write(new_content)

print("Update complete!")
