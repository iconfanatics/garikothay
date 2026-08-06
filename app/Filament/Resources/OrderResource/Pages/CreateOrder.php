<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Handle deduplication of items in the cart
        if (isset($data['items']) && is_array($data['items'])) {
            $mergedItems = [];
            foreach ($data['items'] as $item) {
                $key = $item['product_id'] . '_' . ($item['variant_id'] ?? '0');
                if (isset($mergedItems[$key])) {
                    $mergedItems[$key]['quantity'] += (float) $item['quantity'];
                    $mergedItems[$key]['total_price'] += (float) $item['total_price'];
                } else {
                    $mergedItems[$key] = $item;
                }
            }
            $data['items'] = array_values($mergedItems);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $order = $this->record;
        $data = $this->form->getRawState();

        if (!empty($data['manual_payment_amount']) && $data['manual_payment_amount'] > 0) {
            $order->payments()->create([
                'amount' => $data['manual_payment_amount'],
                'transaction_id' => $data['manual_payment_reference'] ?? null,
                'payment_method' => $order->payment_method?->value ?? 'cod',
                'status' => \App\Enums\PaymentStatus::Paid,
                'paid_at' => now(),
            ]);

            // Update order payment status if fully paid
            if ($order->payments()->sum('amount') >= $order->total) {
                $order->update(['payment_status' => \App\Enums\PaymentStatus::Paid]);
            } else {
                $order->update(['payment_status' => \App\Enums\PaymentStatus::Partial]);
            }
        }
    }
}
