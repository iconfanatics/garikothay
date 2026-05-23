<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['page_id', 'locale', 'title', 'content', 'meta_title', 'meta_description'];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
