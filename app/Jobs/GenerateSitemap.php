<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $sitemap = Sitemap::create();

        $sitemap->add(Url::create('/')->setPriority(1.0)->setChangeFrequency('daily'));
        $sitemap->add(Url::create('/shop')->setPriority(0.9)->setChangeFrequency('daily'));
        $sitemap->add(Url::create('/blog')->setPriority(0.8)->setChangeFrequency('weekly'));

        Product::active()->each(function (Product $product) use ($sitemap) {
            $sitemap->add(
                Url::create(route('shop.show', $product->slug))
                    ->setPriority(0.8)
                    ->setChangeFrequency('weekly')
                    ->setLastModificationDate($product->updated_at)
            );
        });

        Category::active()->each(function (Category $category) use ($sitemap) {
            $sitemap->add(
                Url::create(route('shop.index', ['category' => $category->slug]))
                    ->setPriority(0.7)
                    ->setChangeFrequency('weekly')
            );
        });

        Blog::published()->each(function (Blog $blog) use ($sitemap) {
            $sitemap->add(
                Url::create(route('blog.show', $blog->slug))
                    ->setPriority(0.6)
                    ->setChangeFrequency('monthly')
                    ->setLastModificationDate($blog->updated_at)
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
