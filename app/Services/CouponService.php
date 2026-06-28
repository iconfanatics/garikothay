<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Coupon;
use App\Repositories\Contracts\CouponRepositoryInterface;

class CouponService
{
    public function __construct(
        private readonly CouponRepositoryInterface $couponRepository,
    ) {}

    public function validate(string $code, \App\Models\Cart $cart, ?\App\Models\User $user = null): array
    {
        $coupon = $this->couponRepository->findValidByCode($code);

        if (!$coupon || !$coupon->isValid($user)) {
            return ['valid' => false, 'message' => 'Invalid, expired, or restricted coupon code.'];
        }

        if ($coupon->min_order_amount && $cart->subtotal < $coupon->min_order_amount) {
            return [
                'valid' => false,
                'message' => "Minimum order amount is ৳" . number_format($coupon->min_order_amount, 2),
            ];
        }
        
        $discountAmount = $coupon->calculateDiscount($cart);
        if ($discountAmount <= 0) {
            return ['valid' => false, 'message' => 'This coupon is not applicable to the items in your cart.'];
        }

        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount' => $discountAmount,
        ];
    }

    public function incrementUsage(Coupon $coupon): void
    {
        $coupon->increment('used_count');
    }
}
