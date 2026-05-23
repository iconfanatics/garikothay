<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Support\Facades\Cache;

class ClearProductCache
{
    public function handle(OrderPlaced $event): void
    {
        foreach ($event->order->items as $item) {
            Cache::forget("product:{$item->product->slug}");
        }
        Cache::forget('products:featured');
        Cache::forget('products:new_arrivals');
    }
}
