<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Notifications\Traits\PreventsDuplicateNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailOtpNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use PreventsDuplicateNotifications;

    /**
     * How long before another OTP can be sent to this user.
     * We set a shorter duration for OTPs (e.g., 1 minute).
     */
    protected $duplicateCheckDuration;

    public function __construct(
        public readonly string $otp,
        public readonly string $referenceId,
        public readonly string $expiry = '10 minutes',
        public readonly ?string $verifyUrl = null
    ) {
        $this->duplicateCheckDuration = now()->addMinutes(1);
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Define the unique identifier for duplicate checking.
     * We don't want to block ALL OTPs, just consecutive rapid fires.
     */
    protected function getDuplicateIdentifier(): string
    {
        return 'otp_request';
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verify Your Email Address | ' . \App\Models\Setting::get('site_name', 'Garikothay'))
            ->view('emails.verify_email_otp', [
                'name' => $notifiable->name ?? 'Customer',
                'otp' => $this->otp,
                'expiry' => $this->expiry,
                'reference_id' => $this->referenceId,
                'verify_url' => $this->verifyUrl ?? route('verification.notice'),
            ]);
    }
}
