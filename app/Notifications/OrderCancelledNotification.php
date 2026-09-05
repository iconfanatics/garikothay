<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Order;
use App\Notifications\Traits\PreventsDuplicateNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use PreventsDuplicateNotifications;

    public function __construct(
        public readonly Order $order,
        public readonly ?string $cancellationReason = null
    ) {}

    protected function getDuplicateIdentifier(): string
    {
        return 'order_cancelled_' . $this->order->id;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Order Cancelled: {$this->order->order_number} | " . \App\Models\Setting::get('site_name', 'Garikothay'))
            ->view('emails.orders.cancelled', [
                'order' => $this->order,
                'cancellationReason' => $this->cancellationReason,
            ]);
    }
}
