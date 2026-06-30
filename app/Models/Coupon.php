<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CouponType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value', 'min_order_amount', 'max_discount_amount',
        'usage_limit', 'used_count', 'starts_at', 'expires_at', 'is_active',
        'applicable_type', 'is_first_order_only', 'per_customer_limit'
    ];

    protected function casts(): array
    {
        return [
            'type' => CouponType::class,
            'value' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'max_discount_amount' => 'decimal:2',
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_first_order_only' => 'boolean',
            'per_customer_limit' => 'integer'
        ];
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()));
    }

    public function isValid(?\App\Models\User $user = null): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;

        if ($this->is_first_order_only) {
            if (!$user) return false;
            $orderCount = \App\Models\Order::where('user_id', $user->id)
                ->whereNotIn('status', ['cancelled', 'refunded'])
                ->count();
            if ($orderCount > 0) return false;
        }

        if ($this->per_customer_limit && $user) {
            $usedCount = \App\Models\Order::where('user_id', $user->id)
                ->where('coupon_id', $this->id)
                ->whereNotIn('status', ['cancelled'])
                ->count();
            if ($usedCount >= $this->per_customer_limit) return false;
        }

        return true;
    }

    public function calculateDiscount(\App\Models\Cart $cart): float
    {
        $applicableSubtotal = 0.0;
        
        if ($this->applicable_type === 'products') {
            $productIds = $this->products()->pluck('products.id')->toArray();
            foreach ($cart->items as $item) {
                if (in_array($item->product_id, $productIds)) {
                    $applicableSubtotal += $item->total_price;
                }
            }
        } elseif ($this->applicable_type === 'categories') {
            $categoryIds = $this->categories()->pluck('categories.id')->toArray();
            foreach ($cart->items as $item) {
                if ($item->product && in_array($item->product->category_id, $categoryIds)) {
                    $applicableSubtotal += $item->total_price;
                }
            }
        } else {
            $applicableSubtotal = $cart->subtotal;
        }

        if ($applicableSubtotal <= 0) {
            return 0.0;
        }

        $discount = match($this->type) {
            CouponType::Fixed => (float) $this->value,
            CouponType::Percentage => $applicableSubtotal * ((float) $this->value / 100),
        };

        if ($this->max_discount_amount) {
            $discount = min($discount, (float) $this->max_discount_amount);
        }

        return min($discount, $applicableSubtotal);
    }
}
