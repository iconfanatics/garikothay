<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus: string
{
    case Unpaid = 'unpaid';
    case PartiallyPaid = 'partially_paid';
    case Paid = 'paid';
    case PartiallyRefunded = 'partially_refunded';
    case Refunded = 'refunded';
    case PaymentFailed = 'payment_failed';
    case PaymentCancelled = 'payment_cancelled';

    public function label(): string
    {
        return match($this) {
            self::Unpaid => 'Unpaid',
            self::PartiallyPaid => 'Partially Paid',
            self::Paid => 'Paid',
            self::PartiallyRefunded => 'Partially Refunded',
            self::Refunded => 'Refunded',
            self::PaymentFailed => 'Payment Failed',
            self::PaymentCancelled => 'Payment Cancelled',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Unpaid => 'danger',
            self::PartiallyPaid => 'info',
            self::Paid => 'success',
            self::PartiallyRefunded => 'warning',
            self::Refunded => 'gray',
            self::PaymentFailed => 'danger',
            self::PaymentCancelled => 'warning',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn (self $case) => [$case->value => $case->label()]
        )->toArray();
    }

    /** @return self[] */
    public function allowedTransitions(): array
    {
        return match($this) {
            self::Unpaid => [self::PartiallyPaid, self::Paid, self::PaymentFailed, self::PaymentCancelled],
            self::PartiallyPaid => [self::Paid, self::PartiallyRefunded, self::Refunded],
            self::Paid => [self::PartiallyRefunded, self::Refunded],
            self::PartiallyRefunded => [self::Refunded],
            self::PaymentFailed => [self::Unpaid, self::Paid],
            self::PaymentCancelled => [self::Unpaid, self::Paid],
            default => [],
        };
    }
}
