<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserListing extends Model
{
    protected $fillable = [
        'user_id',
        'reference',
        'title',
        'type',
        'price',
        'location',
        'views',
        'status',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'views' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return str($this->status)->replace('_', ' ')->title()->toString();
    }

    public function getTypeLabelAttribute(): string
    {
        return str($this->type)->replace('_', ' ')->title()->toString();
    }
}
