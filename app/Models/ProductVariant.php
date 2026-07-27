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
        'stock_quantity', 'low_stock_threshold', 'image_gallery', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'price_modifier' => 'decimal:2',
            'image_gallery' => 'array',
            'is_active' => 'boolean',
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

    public function getFinalPriceAttribute(): float
    {
        return $this->price ?? (float) ($this->product->price + $this->price_modifier);
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', true);
    }
}
