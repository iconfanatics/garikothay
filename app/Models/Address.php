<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AddressLabel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'label', 'full_name', 'phone',
        'address_line_1', 'address_line_2', 'city',
        'district', 'division', 'postal_code', 'is_default',
    ];

    protected function casts(): array
    {
        return [
            'label' => AddressLabel::class,
            'is_default' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line_1,
            $this->address_line_2,
            $this->city,
            $this->district,
            $this->division,
            $this->postal_code,
        ]);

        return implode(', ', $parts);
    }
}
