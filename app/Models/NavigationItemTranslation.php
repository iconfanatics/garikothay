<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationItemTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'navigation_item_id',
        'locale',
        'label',
    ];
}
