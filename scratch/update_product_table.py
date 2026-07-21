import os

file_path = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/ProductResource.php"

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
            ->columns([
                Tables\\Columns\\TextColumn::make('id')->label('Product ID')->sortable()->searchable(),
                Tables\\Columns\\ImageColumn::make('primary_image')
                    ->label('Thumbnail')
                    ->state(function (Product $record) {
                        return $record->images->first()?->path;
                    })
                    ->square(),
                Tables\\Columns\\TextColumn::make('translations.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('price')
                    ->label('Price')
                    ->money('BDT')
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->numeric()
                    ->sortable(),
                Tables\\Columns\\BadgeColumn::make('publish_status')
                    ->label('Status')
                    ->colors([
                        'danger' => 'Archived',
                        'warning' => 'Draft',
                        'success' => 'Published',
                        'primary' => 'Scheduled',
                        'secondary' => 'Unpublished',
                    ]),
                Tables\\Columns\\TextColumn::make('created_at')
                    ->label('Created Date')
                    ->dateTime('d M Y')
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\\Filters\\TrashedFilter::make(),
                Tables\\Filters\\SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name),
                Tables\\Filters\\SelectFilter::make('brand')
                    ->label('Brand')
                    ->options(fn () => Product::query()->distinct()->whereNotNull('brand')->pluck('brand', 'brand')->toArray()),
                Tables\\Filters\\SelectFilter::make('publish_status')
                    ->label('Publish Status')
                    ->options([
                        'Draft' => 'Draft',
                        'Scheduled' => 'Scheduled',
                        'Published' => 'Published',
                        'Unpublished' => 'Unpublished',
                        'Archived' => 'Archived',
                    ]),
            ])
            ->actions([
                Tables\\Actions\\EditAction::make(),
                Tables\\Actions\\DeleteAction::make(),
                Tables\\Actions\\RestoreAction::make(),
                Tables\\Actions\\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\\Actions\\BulkActionGroup::make([
                    Tables\\Actions\\DeleteBulkAction::make(),
                    Tables\\Actions\\RestoreBulkAction::make(),
                    Tables\\Actions\\ForceDeleteBulkAction::make(),
                    Tables\\Actions\\BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => true, 'publish_status' => 'Published'])),
                    Tables\\Actions\\BulkAction::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => false, 'publish_status' => 'Unpublished'])),
                    Tables\\Actions\\BulkAction::make('archive')
                        ->label('Archive')
                        ->icon('heroicon-o-archive-box')
                        ->action(fn ($records) => $records->each->update(['publish_status' => 'Archived'])),
                    Tables\\Actions\\BulkAction::make('change_category')
                        ->label('Change Category')
                        ->icon('heroicon-o-tag')
                        ->form([
                            Forms\\Components\\Select::make('category_id')
                                ->label('New Category')
                                ->options(Category::with('translations')->get()->pluck('name', 'id'))
                                ->required(),
                        ])
                        ->action(fn ($records, array $data) => $records->each->update(['category_id' => $data['category_id']])),
                    Tables\\Actions\\BulkAction::make('change_brand')
                        ->label('Change Brand')
                        ->icon('heroicon-o-briefcase')
                        ->form([
                            Forms\\Components\\TextInput::make('brand')
                                ->label('New Brand')
                                ->required(),
                        ])
                        ->action(fn ($records, array $data) => $records->each->update(['brand' => $data['brand']])),
                    Tables\\Actions\\BulkAction::make('download')
                        ->label('Download Export (CSV)')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($records) {
                            $headers = array(
                                "Content-type"        => "text/csv",
                                "Content-Disposition" => "attachment; filename=products_export.csv",
                                "Pragma"              => "no-cache",
                                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                                "Expires"             => "0"
                            );
                            $columns = array('ID', 'Name', 'SKU', 'Price', 'Stock', 'Status');
                            $callback = function() use($records, $columns) {
                                $file = fopen('php://output', 'w');
                                fputcsv($file, $columns);
                                foreach ($records as $record) {
                                    fputcsv($file, array($record->id, $record->name, $record->sku, $record->price, $record->stock_quantity, $record->publish_status));
                                }
                                fclose($file);
                            };
                            return response()->stream($callback, 200, $headers);
                        }),
                ]),
            ]);
    }

    """

new_content = content[:start_idx] + table_replacement + content[end_idx:]

with open(file_path, "w") as f:
    f.write(new_content)

print("Table update complete!")
