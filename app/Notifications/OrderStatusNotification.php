<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Order $order,
        public readonly OrderStatus $newStatus,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Order Update: {$this->order->order_number} — {$this->newStatus->label()}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("Your order **{$this->order->order_number}** status has been updated.")
            ->line("**New Status:** {$this->newStatus->label()}")
            ->when($this->newStatus === OrderStatus::Shipped, fn ($mail) =>
                $mail->line("Your order is on its way! 🚚")
            )
            ->when($this->newStatus === OrderStatus::Delivered, fn ($mail) =>
                $mail->line("Your order has been delivered. Enjoy your premium computer accessories! 💻")
            )
            ->when($this->newStatus === OrderStatus::Cancelled, fn ($mail) =>
                $mail->line("We're sorry to inform you that your order has been cancelled.")
                     ->line("If you have already paid, a refund process will be initiated shortly. Please contact support if you have any questions.")
            )
            ->when($this->newStatus === OrderStatus::Refunded, fn ($mail) =>
                $mail->line("Your order payment has been successfully refunded.")
                     ->line("The amount should reflect in your account within 3-5 business days depending on your payment method.")
            )
            ->action('View Order', route('customer.order.show', $this->order->order_number))
            ->salutation(\App\Models\Setting::get('site_name', 'Garikothay') . ' Team 💻');
    }
}
