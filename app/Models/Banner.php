<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BannerType;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Banner extends Model
{
    use HasTranslations;

    protected $fillable = [
        'type', 'image', 'mobile_image', 'link', 'sort_order', 'starts_at', 'expires_at', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type' => BannerType::class,
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(BannerTranslation::class);
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()));
    }

    public function getTitleAttribute(): ?string
    {
        return $this->getTranslation('title');
    }
}
