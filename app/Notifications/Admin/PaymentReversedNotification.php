<?php

declare(strict_types=1);

namespace App\Notifications\Admin;

use App\Notifications\Traits\PreventsDuplicateNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReversedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use PreventsDuplicateNotifications;

    public function __construct(
        public readonly array $data = []
    ) {}

    protected function getDuplicateIdentifier(): string
    {
        return 'admin_payment_reversed_' . md5(json_encode($this->data));
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Reversed | Garikothay')
            ->view('emails.admin.payment_reversed', [
                'notifiable' => $notifiable,
                'data' => $this->data,
            ]);
    }
}