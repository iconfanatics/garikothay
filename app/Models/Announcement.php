<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'summary',
        'type',
        'starts_at',
        'expires_at',
        'is_active',
        'display_location',
        'priority',
        'button_text',
        'button_url',
        'open_in_new_tab',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
            'open_in_new_tab' => 'boolean',
            'priority' => 'integer',
        ];
    }
}
