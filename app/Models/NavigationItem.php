<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NavigationItem extends Model
{
    use HasTranslations;

    protected $fillable = [
        'group',
        'url',
        'sort_order',
        'is_active',
        'translations',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(NavigationItemTranslation::class);
    }

    public function getLabelAttribute(): ?string
    {
        return $this->getTranslation('label');
    }
}
