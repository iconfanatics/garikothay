<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\OrderPlaced;
use App\Events\OrderStatusChanged;
use App\Events\UserRegistered;
use App\Listeners\ClearProductCache;
use App\Listeners\SendOrderConfirmationEmail;
use App\Listeners\SendOrderStatusUpdateEmail;
use App\Listeners\SendWelcomeEmail;
use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Observers\SupplierObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        Order::observe(OrderObserver::class);
        Supplier::observe(SupplierObserver::class);

        Event::listen(OrderPlaced::class, SendOrderConfirmationEmail::class);
        Event::listen(OrderPlaced::class, ClearProductCache::class);
        Event::listen(OrderStatusChanged::class, SendOrderStatusUpdateEmail::class);
        Event::listen(UserRegistered::class, SendWelcomeEmail::class);
        Event::listen(\Illuminate\Auth\Events\Login::class, \App\Listeners\UpdateLastLogin::class);

        // Global cache clearing hooks
        $clearFrontendCache = function () {
            foreach (['en', 'bn'] as $locale) {
                \Illuminate\Support\Facades\Cache::forget("homepage_data_{$locale}");
                \Illuminate\Support\Facades\Cache::forget("navbar_categories_{$locale}");
                \Illuminate\Support\Facades\Cache::forget("navbar_top_menu_{$locale}");
            }
        };

        \App\Models\Banner::saved($clearFrontendCache);
        \App\Models\Banner::deleted($clearFrontendCache);
        
        \App\Models\Category::saved($clearFrontendCache);
        \App\Models\Category::deleted($clearFrontendCache);
        
        \App\Models\Product::saved($clearFrontendCache);
        \App\Models\Product::deleted($clearFrontendCache);
        
        \App\Models\Review::saved($clearFrontendCache);
        \App\Models\Review::deleted($clearFrontendCache);
        
        \App\Models\Blog::saved($clearFrontendCache);
        \App\Models\Blog::deleted($clearFrontendCache);
        
        if (class_exists(\App\Models\NavigationItem::class)) {
            \App\Models\NavigationItem::saved($clearFrontendCache);
            \App\Models\NavigationItem::deleted($clearFrontendCache);
        }

        \Illuminate\Support\Facades\Gate::before(function ($user, $ability, $arguments = []) {
            if ($user instanceof \App\Models\Admin && $user->is_super_admin) {
                if (in_array($ability, ['delete', 'forceDelete']) && isset($arguments[0])) {
                    $model = $arguments[0];
                    if ($model instanceof \Spatie\Permission\Models\Role && $model->name === 'super_admin') {
                        return null;
                    }
                    if ($model instanceof \App\Models\Admin && $model->id === 1) {
                        return null;
                    }
                }
                return true;
            }
        });
    }
}
