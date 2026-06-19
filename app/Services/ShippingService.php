<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;

class ShippingService
{
    private array $zones = [
        'Dhaka' => ['base' => 60, 'per_kg' => 20],
        'Chittagong' => ['base' => 100, 'per_kg' => 30],
        'Rajshahi' => ['base' => 120, 'per_kg' => 35],
        'Khulna' => ['base' => 120, 'per_kg' => 35],
        'Sylhet' => ['base' => 130, 'per_kg' => 40],
        'Barisal' => ['base' => 130, 'per_kg' => 40],
        'Rangpur' => ['base' => 130, 'per_kg' => 40],
        'Mymensingh' => ['base' => 100, 'per_kg' => 30],
    ];

    public function calculate(string $division, int $weightGrams = 0): float
    {
        return max(0, (float) Setting::get('shipping_charge', 120));
    }

    public function getDeliveryTime(): string
    {
        return (string) Setting::get('delivery_time', '2-5 business days');
    }

    public function getDeliveryPartner(): string
    {
        return (string) Setting::get('delivery_partner', 'Steadfast');
    }

    public function isFreeShipping(float $orderTotal): bool
    {
        $threshold = (float) Setting::get('free_shipping_threshold', 1500);

        return $threshold > 0 && $orderTotal >= $threshold;
    }

    public function getDivisions(): array
    {
        return array_keys($this->zones);
    }
}
