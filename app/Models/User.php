<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'locale',
        'is_active',
        'email_verified_at',
        'phone_verified_at',
        'address',
        'division',
        'district',
        'preferred_payment_method',
        'notes',
        'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function serviceBookings(): HasMany
    {
        return $this->hasMany(ServiceBooking::class);
    }

    public function listings(): HasMany
    {
        return $this->hasMany(UserListing::class);
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', true);
    }

    public function getDefaultAddressAttribute(): ?Address
    {
        return $this->addresses->firstWhere('is_default', true);
    }

    public function getTotalSpentAttribute(): float
    {
        return (float) $this->orders()
            ->whereNotIn('status', [
                OrderStatus::Cancelled->value,
                OrderStatus::Returned->value,
                OrderStatus::Refunded->value,
            ])
            ->sum('total');
    }
}
