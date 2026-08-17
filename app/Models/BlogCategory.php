<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends Model
{
    use HasTranslations;

    protected $fillable = [
        'slug', 'is_active', 'image',
        'meta_title', 'meta_description', 'sort_order', 'is_featured', 'show_on_homepage', 'translations'
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(BlogCategoryTranslation::class);
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    public function getNameAttribute(): ?string
    {
        return $this->getTranslation('name');
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->getTranslation('description');
    }
}
