<?php

declare(strict_types=1);

namespace App\Notifications\Admin;

use App\Notifications\Traits\PreventsDuplicateNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShipmentDamagedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use PreventsDuplicateNotifications;

    public function __construct(
        public readonly array $data = []
    ) {}

    protected function getDuplicateIdentifier(): string
    {
        return 'admin_shipment_damaged_' . md5(json_encode($this->data));
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Shipment Damaged | Garikothay')
            ->view('emails.admin.shipment_damaged', [
                'notifiable' => $notifiable,
                'data' => $this->data,
            ]);
    }
}