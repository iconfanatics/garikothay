import os

resource_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/BlogCommentResource.php"
with open(resource_file, 'r') as f:
    content = f.read()

form_schema = """
            ->schema([
                Forms\\Components\\Section::make('Comment Details')->schema([
                    Forms\\Components\\Select::make('blog_id')
                        ->relationship('blog', 'id')
                        ->getOptionLabelFromRecordUsing(fn ($record) => $record->title)
                        ->disabled(),
                    Forms\\Components\\TextInput::make('name')
                        ->disabled(),
                    Forms\\Components\\TextInput::make('email')
                        ->disabled(),
                    Forms\\Components\\Textarea::make('comment')
                        ->columnSpanFull()
                        ->disabled(),
                    Forms\\Components\\Toggle::make('is_approved')
                        ->label('Approved')
                        ->helperText('Approve this comment to show it on the blog.'),
                ])->columns(2),
            ]);"""

table_columns = """
            ->columns([
                Tables\\Columns\\TextColumn::make('blog.title')
                    ->label('Blog Post')
                    ->searchable(query: function ($query, string $search): void {
                        $query->whereHas('blog.translations', fn ($q) => $q->where('title', 'like', "%{$search}%"));
                    })
                    ->sortable()
                    ->limit(30),
                Tables\\Columns\\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\\Columns\\TextColumn::make('comment')
                    ->limit(50),
                Tables\\Columns\\ToggleColumn::make('is_approved')
                    ->label('Approved'),
                Tables\\Columns\\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\\Filters\\TernaryFilter::make('is_approved')
                    ->label('Approval Status'),
            ])
            ->actions([
                Tables\\Actions\\EditAction::make(),
                Tables\\Actions\\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\\Actions\\BulkActionGroup::make([
                    Tables\\Actions\\BulkAction::make('approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_approved' => true])),
                    Tables\\Actions\\BulkAction::make('reject')
                        ->label('Reject Selected')
                        ->icon('heroicon-o-x-mark')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->update(['is_approved' => false])),
                    Tables\\Actions\\DeleteBulkAction::make(),
                ]),
            ])"""

content = content.replace("            ->schema([\n                //\n            ]);", form_schema)
content = content.replace("            ->columns([\n                //\n            ])\n            ->filters([\n                //\n            ])\n            ->actions([\n                Tables\\Actions\\EditAction::make(),\n            ])\n            ->bulkActions([\n                Tables\\Actions\\BulkActionGroup::make([\n                    Tables\\Actions\\DeleteBulkAction::make(),\n                ]),\n            ])", table_columns)
content = content.replace("protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';", "protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';\n    protected static ?string $navigationGroup = 'Content Management';")

with open(resource_file, 'w') as f:
    f.write(content)

print("BlogCommentResource patched!")
