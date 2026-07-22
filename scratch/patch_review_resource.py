import os

file_path = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/ReviewResource.php"

with open(file_path, 'r') as f:
    content = f.read()

form_replacement = """    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\\Components\\Section::make('Review Details')
                    ->schema([
                        Forms\\Components\\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->disabled(),
                        Forms\\Components\\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->disabled(),
                        Forms\\Components\\TextInput::make('rating')
                            ->numeric()
                            ->disabled(),
                        Forms\\Components\\TextInput::make('title')
                            ->disabled(),
                        Forms\\Components\\Textarea::make('comment')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\\Components\\Toggle::make('is_approved')
                            ->label('Approved for Display'),
                    ])->columns(2),
                Forms\\Components\\Section::make('Admin Interaction')
                    ->schema([
                        Forms\\Components\\Textarea::make('admin_reply')
                            ->label('Admin Reply')
                            ->hint('This reply will be visible to customers on the product page.')
                            ->columnSpanFull(),
                    ])
            ]);
    }"""

table_replacement = """    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\\Columns\\TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\\Columns\\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('rating')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => str_repeat('⭐', $state)),
                Tables\\Columns\\IconColumn::make('is_approved')
                    ->label('Approved')
                    ->boolean()
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\\Filters\\TernaryFilter::make('is_approved')
                    ->label('Approval Status'),
                Tables\\Filters\\SelectFilter::make('rating')
                    ->options([
                        5 => '5 Stars',
                        4 => '4 Stars',
                        3 => '3 Stars',
                        2 => '2 Stars',
                        1 => '1 Star',
                    ]),
            ])
            ->actions([
                Tables\\Actions\\EditAction::make()->label('Moderate/Reply'),
            ])
            ->bulkActions([
                Tables\\Actions\\BulkActionGroup::make([
                    Tables\\Actions\\BulkAction::make('Approve Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (\\Illuminate\\Database\\Eloquent\\Collection $records) => $records->each->update(['is_approved' => true])),
                    Tables\\Actions\\BulkAction::make('Reject Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn (\\Illuminate\\Database\\Eloquent\\Collection $records) => $records->each->update(['is_approved' => false])),
                    Tables\\Actions\\DeleteBulkAction::make(),
                ]),
            ]);
    }"""

import re
content = re.sub(r'public static function form\(Form \$form\): Form\s*\{.*?\}(?=\s*public static function table)', form_replacement, content, flags=re.DOTALL)
content = re.sub(r'public static function table\(Table \$table\): Table\s*\{.*?\}(?=\s*public static function getRelations)', table_replacement, content, flags=re.DOTALL)

with open(file_path, 'w') as f:
    f.write(content)
print("ReviewResource patched!")
