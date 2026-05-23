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
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        Order::observe(OrderObserver::class);

        Event::listen(OrderPlaced::class, SendOrderConfirmationEmail::class);
        Event::listen(OrderPlaced::class, ClearProductCache::class);
        Event::listen(OrderStatusChanged::class, SendOrderStatusUpdateEmail::class);
        Event::listen(UserRegistered::class, SendWelcomeEmail::class);
    }
}
