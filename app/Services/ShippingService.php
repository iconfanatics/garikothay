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

    public function calculate(string $division, ?string $city = null, int $weightGrams = 0): float
    {
        return $this->isDhakaCity($division, $city)
            ? $this->getDhakaCityCharge()
            : $this->getOutsideDhakaCharge();
    }

    public function getDhakaCityCharge(): float
    {
        return max(0, (float) Setting::get('dhaka_city_shipping_charge', 80));
    }

    public function getOutsideDhakaCharge(): float
    {
        return max(0, (float) Setting::get(
            'outside_dhaka_shipping_charge',
            Setting::get('shipping_charge', 150),
        ));
    }

    public function isDhakaCity(string $division, ?string $city): bool
    {
        $normalizedDivision = mb_strtolower(trim($division));
        $normalizedCity = mb_strtolower(trim((string) $city));

        return in_array($normalizedDivision, ['dhaka', 'ঢাকা'], true)
            && in_array($normalizedCity, ['dhaka', 'dhaka city', 'dacca', 'ঢাকা', 'ঢাকা সিটি'], true);
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
