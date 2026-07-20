<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'name', 'sku', 
        'price', 'compare_price', 'price_modifier', 
        'stock_quantity', 'image_gallery', 'is_active'
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
