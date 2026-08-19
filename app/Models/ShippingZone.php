<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'zone_type', 'districts', 'coverage_areas', 'is_active', 'is_cod_enabled'];

    protected function casts(): array
    {
        return [
            'districts' => 'array',
            'coverage_areas' => 'array',
            'is_active' => 'boolean',
            'is_cod_enabled' => 'boolean',
        ];
    }

    public function shippingMethods(): HasMany
    {
        return $this->hasMany(ShippingMethod::class);
    }
}
