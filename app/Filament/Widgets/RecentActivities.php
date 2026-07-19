<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Coupon;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class RecentActivities extends Widget
{
    protected static string $view = 'filament.widgets.recent-activities';
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $activities = collect();

        // Recent Orders
        Order::latest()->take(5)->get()->each(function ($order) use (&$activities) {
            $activities->push([
                'type' => 'order',
                'title' => 'New Order Placed',
                'description' => "Order #{$order->order_number} for ৳{$order->total}",
                'time' => $order->created_at,
                'icon' => 'heroicon-m-shopping-bag',
                'color' => 'success',
            ]);
        });

        // Cancelled Orders
        Order::where('status', 'cancelled')->latest('updated_at')->take(5)->get()->each(function ($order) use (&$activities) {
            $activities->push([
                'type' => 'order_cancelled',
                'title' => 'Order Cancelled',
                'description' => "Order #{$order->order_number} was cancelled.",
                'time' => $order->updated_at,
                'icon' => 'heroicon-m-x-circle',
                'color' => 'danger',
            ]);
        });

        // Recently Added Products
        Product::latest()->take(5)->get()->each(function ($product) use (&$activities) {
            $activities->push([
                'type' => 'product_added',
                'title' => 'Product Added',
                'description' => "{$product->name} was added to the catalog.",
                'time' => $product->created_at,
                'icon' => 'heroicon-m-cube',
                'color' => 'primary',
            ]);
        });

        // Recently Updated Products
        Product::whereColumn('updated_at', '>', 'created_at')->latest('updated_at')->take(5)->get()->each(function ($product) use (&$activities) {
            $activities->push([
                'type' => 'product_updated',
                'title' => 'Product Updated',
                'description' => "{$product->name} was updated.",
                'time' => $product->updated_at,
                'icon' => 'heroicon-m-pencil-square',
                'color' => 'warning',
            ]);
        });

        // Recent Reviews
        Review::latest()->take(5)->get()->each(function ($review) use (&$activities) {
            $activities->push([
                'type' => 'review',
                'title' => 'New Review',
                'description' => "{$review->rating}-star review on {$review->product->name}.",
                'time' => $review->created_at,
                'icon' => 'heroicon-m-star',
                'color' => 'info',
            ]);
        });

        // Used Coupons (Orders with coupon_id)
        Order::whereNotNull('coupon_id')->latest()->take(5)->get()->each(function ($order) use (&$activities) {
            $activities->push([
                'type' => 'coupon_used',
                'title' => 'Coupon Used',
                'description' => "Coupon '{$order->coupon->code}' was used in Order #{$order->order_number}.",
                'time' => $order->created_at,
                'icon' => 'heroicon-m-ticket',
                'color' => 'success',
            ]);
        });

        $sortedActivities = $activities->sortByDesc('time')->take(10)->values();

        return [
            'activities' => $sortedActivities,
        ];
    }
}
