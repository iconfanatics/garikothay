<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'variant_type_id', 'variant_value_id', 'name', 'sku', 
        'price', 'compare_price', 'price_modifier', 
        'stock_quantity', 'low_stock_threshold', 'image_gallery', 'is_active',
        'discount_type', 'discount_amount', 'discount_start_date', 'discount_end_date',
        'cost_price', 'scheduled_price', 'price_effective_date', 'minimum_selling_price',
        'weight_value', 'weight_unit', 'length', 'width', 'height', 'dimension_unit',
    ];

    protected static function booted(): void
    {
        static::creating(function (ProductVariant $variant): void {
            if (empty($variant->sku)) {
                $variant->sku = 'VAR-' . strtoupper(\Illuminate\Support\Str::random(8));
            }
        });
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'price_modifier' => 'decimal:2',
            'image_gallery' => 'array',
            'is_active' => 'boolean',
            'discount_amount' => 'decimal:2',
            'discount_start_date' => 'datetime',
            'discount_end_date' => 'datetime',
        ];
    }

    public function variantType()
    {
        return $this->belongsTo(VariantType::class);
    }

    public function variantValue()
    {
        return $this->belongsTo(VariantValue::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getNameAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        if ($this->relationLoaded('variantType') && $this->relationLoaded('variantValue')) {
            if ($this->variantType && $this->variantValue) {
                return $this->variantType->name . ': ' . $this->variantValue->name;
            }
        } else {
            $this->loadMissing(['variantType', 'variantValue']);
            if ($this->variantType && $this->variantValue) {
                return $this->variantType->name . ': ' . $this->variantValue->name;
            }
        }

        return 'Variant #' . $this->id;
    }

    public function getActiveDiscountAmountAttribute(): float
    {
        if (!$this->discount_amount || !$this->discount_type) {
            return 0;
        }

        if ($this->discount_start_date && $this->discount_start_date > now()) {
            return 0;
        }

        if ($this->discount_end_date && $this->discount_end_date < now()) {
            return 0;
        }

        $basePrice = $this->price > 0 ? $this->price : $this->product->price + $this->price_modifier;

        if ($this->discount_type === 'Percentage') {
            return (float) $basePrice * ($this->discount_amount / 100);
        }

        return (float) $this->discount_amount;
    }

    public function getSellingPriceAttribute(): float
    {
        $basePrice = $this->price > 0 ? (float)$this->price : (float)$this->product->selling_price + (float)$this->price_modifier;
        
        if ($this->active_discount_amount > 0) {
            $basePrice = $this->price > 0 ? (float)$this->price : (float)$this->product->price + (float)$this->price_modifier;
            return (float) max(0, $basePrice - $this->active_discount_amount);
        }

        return (float) max(0, $basePrice);
    }

    public function getOriginalPriceAttribute(): float
    {
        $basePrice = $this->price > 0 ? (float)$this->price : (float)$this->product->price + (float)$this->price_modifier;

        if ($this->active_discount_amount > 0) {
            return $this->compare_price > $basePrice ? (float) $this->compare_price : (float)$basePrice;
        }

        if (!($this->price > 0) && $this->product->active_discount_amount > 0) {
            return (float)$this->product->original_price + (float)$this->price_modifier;
        }

        return (float) ($this->compare_price ?? $basePrice);
    }

    public function getDiscountPercentageAttribute(): int
    {
        $original = $this->original_price;
        $selling = $this->selling_price;
        
        if ($original > 0 && $original > $selling) {
            return (int) round((($original - $selling) / $original) * 100);
        }
        
        return 0;
    }

    public function getFinalPriceAttribute(): float
    {
        return $this->selling_price;
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', true);
    }
}
