import os

resource_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/ActivityResource.php"
with open(resource_file, 'r') as f:
    content = f.read()

content = content.replace("use App\\Models\\Activity;", "use Spatie\\Activitylog\\Models\\Activity;")

table_columns = """
            ->columns([
                Tables\\Columns\\TextColumn::make('log_name')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\\Columns\\TextColumn::make('description')
                    ->searchable(),
                Tables\\Columns\\TextColumn::make('subject_type')
                    ->label('Subject')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->sortable()
                    ->searchable(),
                Tables\\Columns\\TextColumn::make('causer.name')
                    ->label('Causer')
                    ->sortable()
                    ->searchable(),
                Tables\\Columns\\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')"""

content = content.replace("            ->columns([\n                //\n            ])", table_columns)
content = content.replace("protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';", "protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';\n    protected static ?string $navigationGroup = 'Site Management';\n\n    public static function canCreate(): bool\n    {\n        return false;\n    }")

with open(resource_file, 'w') as f:
    f.write(content)

print("ActivityResource patched!")
