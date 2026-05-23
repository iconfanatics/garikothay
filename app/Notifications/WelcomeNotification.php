<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to ' . \App\Models\Setting::get('site_name', 'Dinajpur IT Park') . '! 💻')
            ->greeting("Welcome, {$notifiable->name}! 💻")
            ->line('Thank you for joining ' . \App\Models\Setting::get('site_name', 'Dinajpur IT Park') . ' — ' . \App\Models\Setting::get('site_tagline', 'Your Ultimate Destination for Premium Computer Accessories') . '.')
            ->line('Discover premium mechanical keyboards, gaming mice, networking devices, and technical hardware.')
            ->action('Start Shopping', route('shop.index'))
            ->line('Use code **WELCOME10** for 10% off your first order!')
            ->salutation('Happy Tech Setup! 🔌 — ' . \App\Models\Setting::get('site_name', 'Dinajpur IT Park') . ' Team');
    }
}
