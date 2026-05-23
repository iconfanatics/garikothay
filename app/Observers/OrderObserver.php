<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Str;

class OrderObserver
{
    public function creating(Order $order): void
    {
        if (empty($order->order_number)) {
            $order->order_number = $this->generateOrderNumber();
        }
    }

    private function generateOrderNumber(): string
    {
        $prefix = 'GNG';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(4));

        $number = "{$prefix}-{$date}-{$random}";

        // Ensure uniqueness
        while (Order::where('order_number', $number)->exists()) {
            $random = strtoupper(Str::random(4));
            $number = "{$prefix}-{$date}-{$random}";
        }

        return $number;
    }
}
