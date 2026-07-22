import os
import re

def create_file(filepath, content):
    os.makedirs(os.path.dirname(filepath), exist_ok=True)
    with open(filepath, 'w') as f:
        f.write(content)

# 1. Create BannerStatsOverview Widget
banner_stats_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Widgets/BannerStatsOverview.php"
banner_stats_content = """<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Banner;

class BannerStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Active Banners', Banner::where('is_active', true)->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))->count())
                ->description('Currently showing')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Scheduled Banners', Banner::where('is_active', true)->whereNotNull('starts_at')->where('starts_at', '>', now())->count())
                ->description('Upcoming')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
            Stat::make('Inactive/Expired Banners', Banner::where('is_active', false)->orWhere(fn ($q) => $q->whereNotNull('expires_at')->where('expires_at', '<', now()))->count())
                ->description('Needs attention')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
"""
create_file(banner_stats_file, banner_stats_content)

# 2. Add to ListBanners
list_banners_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/BannerResource/Pages/ListBanners.php"
with open(list_banners_file, 'r') as f:
    content = f.read()
if "getHeaderWidgets" not in content:
    replacement = """    protected function getHeaderActions(): array
    {
        return [
            Actions\\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \\App\\Filament\\Widgets\\BannerStatsOverview::class,
        ];
    }"""
    content = re.sub(r'protected function getHeaderActions\(\): array\s*\{\s*return \[\s*Actions\\CreateAction::make\(\),\s*\];\s*\}', replacement, content)
    with open(list_banners_file, 'w') as f:
        f.write(content)

# 3. Create BlogStatsOverview Widget
blog_stats_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Widgets/BlogStatsOverview.php"
blog_stats_content = """<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Blog;

class BlogStatsOverview extends BaseWidget
{
    protected function getStats(): array
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
            Stat::make('Drafts', Blog::where('is_published', false)->count())
                ->description('Unpublished')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color('warning'),
        ];
    }
}
"""
create_file(blog_stats_file, blog_stats_content)

# 4. Add to ListBlogs
list_blogs_file = "/home/sany/Desktop/mmm/e-commerce/app/Filament/Resources/BlogResource/Pages/ListBlogs.php"
with open(list_blogs_file, 'r') as f:
    content = f.read()
if "getHeaderWidgets" not in content:
    replacement = """    protected function getHeaderActions(): array
    {
        return [
            Actions\\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \\App\\Filament\\Widgets\\BlogStatsOverview::class,
        ];
    }"""
    content = re.sub(r'protected function getHeaderActions\(\): array\s*\{\s*return \[\s*Actions\\CreateAction::make\(\),\s*\];\s*\}', replacement, content)
    with open(list_blogs_file, 'w') as f:
        f.write(content)

print("Banner and Blog Widgets created and attached!")
