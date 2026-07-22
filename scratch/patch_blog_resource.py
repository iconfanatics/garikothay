import os

# 1. Update BlogResource to add SEO and Tags
resource_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/BlogResource.php"
with open(resource_file, 'r') as f:
    content = f.read()

seo_schema = """            Forms\\Components\\Section::make('SEO & Tags')->schema([
                Forms\\Components\\TagsInput::make('tags')
                    ->label('Tags')
                    ->separator(',')
                    ->columnSpanFull(),
                Forms\\Components\\TextInput::make('seo_title')
                    ->label('SEO Title')
                    ->maxLength(255),
                Forms\\Components\\Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->rows(3)
                    ->columnSpanFull(),
            ]),"""

if "SEO & Tags" not in content:
    content = content.replace("Forms\\Components\\Section::make(\"Publishing Details\")->schema([", seo_schema + "\n            Forms\\Components\\Section::make(\"Publishing Details\")->schema([")
    with open(resource_file, 'w') as f:
        f.write(content)

# 2. Update BlogStatsOverview widget
stats_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Widgets/BlogStatsOverview.php"
with open(stats_file, 'r') as f:
    stats_content = f.read()

new_stats = """    protected function getStats(): array
    {
        return [
            Stat::make('Total Blogs', Blog::count())
                ->description('All time')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
            Stat::make('Published Blogs', Blog::where('is_published', true)->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))->count())
                ->description('Live on site')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Scheduled Posts', Blog::where('is_published', true)->whereNotNull('published_at')->where('published_at', '>', now())->count())
                ->description('Upcoming')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
            Stat::make('Drafts', Blog::where('is_published', false)->count())
                ->description('Unpublished')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color('warning'),
            Stat::make('Total Categories', \\App\\Models\\BlogCategory::count())
                ->color('secondary'),
            Stat::make('Total Comments', \\App\\Models\\BlogComment::count())
                ->description('Needs moderation: ' . \\App\\Models\\BlogComment::where('is_approved', false)->count())
                ->color('primary'),
        ];
    }"""
if "Scheduled Posts" not in stats_content:
    import re
    stats_content = re.sub(r'protected function getStats\(\): array\s*\{.*\}', new_stats, stats_content, flags=re.DOTALL)
    with open(stats_file, 'w') as f:
        f.write(stats_content)

print("BlogResource and Stats widget patched!")
