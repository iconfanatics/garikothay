<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Coupon;
use App\Repositories\Contracts\CouponRepositoryInterface;

class CouponRepository implements CouponRepositoryInterface
{
    public function findValidByCode(string $code): ?Coupon
    {
        return Coupon::active()->where('code', strtoupper($code))->first();
    }
}
