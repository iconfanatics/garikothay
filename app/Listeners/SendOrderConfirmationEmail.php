<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Notifications\OrderConfirmationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderConfirmationEmail implements ShouldQueue
{
    public function handle(OrderPlaced $event): void
    {
        $event->order->user->notify(new OrderConfirmationNotification($event->order));
        
        $adminEmail = config('mail.from.address');
        if ($adminEmail) {
            \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\AdminNewOrderMail($event->order));
        }
    }
}
