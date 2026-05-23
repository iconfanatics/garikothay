<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasTranslations;

    protected $fillable = ['slug'];

    public function translations(): HasMany
    {
        return $this->hasMany(PageTranslation::class);
    }

    public function getTitleAttribute(): ?string
    {
        return $this->getTranslation('title');
    }

    public function getContentAttribute(): ?string
    {
        return $this->getTranslation('content');
    }
}
