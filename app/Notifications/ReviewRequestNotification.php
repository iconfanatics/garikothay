<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Order;
use App\Notifications\Traits\PreventsDuplicateNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use PreventsDuplicateNotifications;

    public function __construct(
        public readonly Order $order,
    ) {}

    protected function getDuplicateIdentifier(): string
    {
        return 'review_request_' . $this->order->id;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('How did you like your recent order from Garikothay?')
            ->view('emails.review-request', [
                'order' => $this->order,
            ]);
    }
}
