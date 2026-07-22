import os

resource_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/AnnouncementResource.php"
with open(resource_file, 'r') as f:
    content = f.read()

form_schema = """
            ->schema([
                Forms\\Components\\Section::make('Announcement Details')->schema([
                    Forms\\Components\\TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    Forms\\Components\\Select::make('type')
                        ->options([
                            'info' => 'Information',
                            'warning' => 'Warning',
                            'promo' => 'Promotional',
                        ])
                        ->default('info')
                        ->required(),
                    Forms\\Components\\Textarea::make('content')
                        ->columnSpanFull(),
                ])->columns(2),
                Forms\\Components\\Section::make('Display Rules')->schema([
                    Forms\\Components\\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                    Forms\\Components\\Grid::make(2)->schema([
                        Forms\\Components\\DateTimePicker::make('starts_at')
                            ->label('Starts At (Optional)'),
                        Forms\\Components\\DateTimePicker::make('expires_at')
                            ->label('Expires At (Optional)'),
                    ]),
                ]),
            ]);"""

table_columns = """
            ->columns([
                Tables\\Columns\\TextColumn::make('title')
                    ->searchable()
                    ->limit(40),
                Tables\\Columns\\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'info',
                        'warning' => 'warning',
                        'success' => 'promo',
                    ]),
                Tables\\Columns\\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\\Columns\\TextColumn::make('starts_at')
                    ->dateTime('d M Y, h:i A')
                    ->placeholder('Immediately')
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('expires_at')
                    ->dateTime('d M Y, h:i A')
                    ->placeholder('Never')
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])"""

content = content.replace("            ->schema([\n                //\n            ]);", form_schema)
content = content.replace("            ->columns([\n                //\n            ])", table_columns)
content = content.replace("protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';", "protected static ?string $navigationIcon = 'heroicon-o-megaphone';\n    protected static ?string $navigationGroup = 'Content Management';")

widgets_func = """    public static function getWidgets(): array
    {
        return [
            Widgets\\AnnouncementStatsOverview::class,
        ];
    }
"""
content = content.replace("public static function getPages(): array", widgets_func + "\n    public static function getPages(): array")

with open(resource_file, 'w') as f:
    f.write(content)

# Patch ListAnnouncements
list_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/AnnouncementResource/Pages/ListAnnouncements.php"
with open(list_file, 'r') as f:
    list_content = f.read()

replacement_list = """    protected function getHeaderActions(): array
    {
        return [
            Actions\\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \\App\\Filament\\Resources\\AnnouncementResource\\Widgets\\AnnouncementStatsOverview::class,
        ];
    }"""
list_content = list_content.replace("    protected function getHeaderActions(): array\n    {\n        return [\n            Actions\\CreateAction::make(),\n        ];\n    }", replacement_list)

with open(list_file, 'w') as f:
    f.write(list_content)

print("Patch complete")
