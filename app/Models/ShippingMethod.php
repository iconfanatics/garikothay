<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_zone_id', 'name', 'base_charge', 'free_shipping_threshold', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'base_charge' => 'decimal:2',
            'free_shipping_threshold' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function shippingZone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class);
    }
}
