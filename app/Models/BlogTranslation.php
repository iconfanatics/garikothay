<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'blog_id', 'locale', 'title', 'excerpt', 'content', 'meta_title', 'meta_description',
    ];

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }
}
