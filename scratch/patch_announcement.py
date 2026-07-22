import os
import re

# 1. Patch AnnouncementStatsOverview
stats_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/AnnouncementResource/Widgets/AnnouncementStatsOverview.php"
with open(stats_file, 'r') as f:
    content = f.read()

replacement = """    protected function getStats(): array
    {
        return [
            Stat::make('Active Announcements', \\App\\Models\\Announcement::where('is_active', true)->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))->count())
                ->description('Currently showing')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Scheduled', \\App\\Models\\Announcement::where('is_active', true)->whereNotNull('starts_at')->where('starts_at', '>', now())->count())
                ->description('Upcoming')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
        ];
    }"""
content = re.sub(r'protected function getStats\(\): array\s*\{\s*return \[\s*//\s*\];\s*\}', replacement, content)
with open(stats_file, 'w') as f:
    f.write(content)


# 2. Patch AnnouncementResource
resource_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/AnnouncementResource.php"
with open(resource_file, 'r') as f:
    content = f.read()

form_schema = """            Forms\\Components\\Section::make('Announcement Details')->schema([
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
            ])"""
table_columns = """                Tables\\Columns\\TextColumn::make('title')
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
                    ->toggleable(isToggledHiddenByDefault: true),"""

content = re.sub(r'public static function form\(Form \$form\): Form\s*\{\s*return \$form\s*->schema\(\[\s*//\s*\]\);', 'public static function form(Form $form): Form\n    {\n        return $form\n            ->schema([\n' + form_schema + '\n            ]);', content)
content = re.sub(r'public static function table\(Table \$table\): Table\s*\{\s*return \$table\s*->columns\(\[\s*//\s*\]\)', 'public static function table(Table $table): Table\n    {\n        return $table\n            ->columns([\n' + table_columns + '\n            ])', content)

# Add getWidgets
if "public static function getWidgets(): array" not in content:
    widgets_func = """    public static function getWidgets(): array
    {
        return [
            Widgets\\AnnouncementStatsOverview::class,
        ];
    }
"""
    content = content.replace("public static function getPages(): array", widgets_func + "\n    public static function getPages(): array")
    content = content.replace("protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';", "protected static ?string $navigationIcon = 'heroicon-o-megaphone';\n    protected static ?string $navigationGroup = 'Content Management';")

with open(resource_file, 'w') as f:
    f.write(content)

# 3. Patch ListAnnouncements
list_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/AnnouncementResource/Pages/ListAnnouncements.php"
with open(list_file, 'r') as f:
    content = f.read()

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
content = re.sub(r'protected function getHeaderActions\(\): array\s*\{\s*return \[\s*Actions\\CreateAction::make\(\),\s*\];\s*\}', replacement_list, content)

with open(list_file, 'w') as f:
    f.write(content)

print("Announcement Resource and Widget patched!")
