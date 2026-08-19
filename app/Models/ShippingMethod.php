<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_zone_id', 'name', 'shipping_type', 'base_charge', 'free_shipping_threshold', 'free_shipping_enabled', 'estimated_delivery_time', 'is_active', 'is_cod_enabled'
    ];

    protected function casts(): array
    {
        return [
            'base_charge' => 'decimal:2',
            'free_shipping_threshold' => 'decimal:2',
            'free_shipping_enabled' => 'boolean',
            'is_active' => 'boolean',
            'is_cod_enabled' => 'boolean',
        ];
    }

    public function shippingZone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class);
    }
}
