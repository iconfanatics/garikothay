<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Notifications\OrderStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderStatusUpdateEmail implements ShouldQueue
{
    public function handle(OrderStatusChanged $event): void
    {
        $event->order->user->notify(
            new OrderStatusNotification($event->order, $event->newStatus)
        );
    }
}
