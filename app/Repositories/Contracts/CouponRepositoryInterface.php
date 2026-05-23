<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Coupon;

interface CouponRepositoryInterface
{
    public function findValidByCode(string $code): ?Coupon;
}
