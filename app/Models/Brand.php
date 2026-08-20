<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    protected $fillable = ["name", "slug", "is_active"];

    protected static function booted()
    {
        static::creating(function ($brand) {
            if (empty($brand->slug)) $brand->slug = Str::slug($brand->name);
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, Product::class);
    }
}
