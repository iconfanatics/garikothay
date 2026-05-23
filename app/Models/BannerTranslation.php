<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BannerTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['banner_id', 'locale', 'title', 'subtitle', 'button_text'];

    public function banner(): BelongsTo
    {
        return $this->belongsTo(Banner::class);
    }
}
