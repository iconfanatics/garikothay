<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Str;

class OrderObserver
{
    public function creating(Order $order): void
    {
        if (empty($order->order_number)) {
            $order->order_number = $this->generateOrderNumber();
        }
    }

    public function created(Order $order): void
    {
        $billingInfo = $order->billing_address ?? $order->shipping_address ?? [];
        
        $order->invoice()->create([
            'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
            'invoice_date' => now(),
            'status' => \App\Enums\InvoiceStatus::Pending,
            'billing_information' => $billingInfo,
        ]);
    }

    public function updated(Order $order): void
    {
        if ($order->wasChanged('payment_status')) {
            $invoice = $order->invoice;
            if ($invoice) {
                if ($order->payment_status === \App\Enums\PaymentStatus::Paid) {
                    $invoice->update(['status' => \App\Enums\InvoiceStatus::Paid]);
                } elseif ($order->payment_status === \App\Enums\PaymentStatus::Refunded) {
                    $invoice->update(['status' => \App\Enums\InvoiceStatus::Cancelled]);
                }
            }
        }
    }

    private function generateOrderNumber(): string
    {
        $prefix = 'GNG';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(4));

        $number = "{$prefix}-{$date}-{$random}";

        // Ensure uniqueness
        while (Order::where('order_number', $number)->exists()) {
            $random = strtoupper(Str::random(4));
            $number = "{$prefix}-{$date}-{$random}";
        }

        return $number;
    }
}
