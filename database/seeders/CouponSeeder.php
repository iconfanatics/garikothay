<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\CouponType;
use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'type' => CouponType::Percentage,
                'value' => 10,
                'min_order_amount' => 500,
                'max_discount_amount' => 200,
                'usage_limit' => 1000,
                'is_active' => true,
            ],
            [
                'code' => 'PLANT20',
                'type' => CouponType::Percentage,
                'value' => 20,
                'min_order_amount' => 1000,
                'max_discount_amount' => 500,
                'usage_limit' => 500,
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP',
                'type' => CouponType::Fixed,
                'value' => 120,
                'min_order_amount' => 800,
                'usage_limit' => null,
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::firstOrCreate(['code' => $coupon['code']], $coupon);
        }
    }
}
