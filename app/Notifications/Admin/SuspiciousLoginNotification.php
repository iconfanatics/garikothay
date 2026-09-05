<?php

declare(strict_types=1);

namespace App\Notifications\Admin;

use App\Notifications\Traits\PreventsDuplicateNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuspiciousLoginNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use PreventsDuplicateNotifications;

    protected $duplicateCheckDuration;

    public function __construct(
        public readonly array $data = []
    ) {
        $this->duplicateCheckDuration = now()->addMinutes(1);
    }

    protected function getDuplicateIdentifier(): string
    {
        return 'admin_suspicious_login_' . md5(json_encode($this->data));
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Suspicious Admin Login Detected | Garikothay')
            ->view('emails.admin.suspicious_login', [
                'notifiable' => $notifiable,
                'data' => $this->data,
            ]);
    }
}