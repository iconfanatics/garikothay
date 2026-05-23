<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $fillable = ['email', 'is_subscribed', 'subscribed_at', 'unsubscribed_at'];

    protected function casts(): array
    {
        return [
            'is_subscribed' => 'boolean',
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    public function scopeSubscribed($query): void
    {
        $query->where('is_subscribed', true);
    }
}
