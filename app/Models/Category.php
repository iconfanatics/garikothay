<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use HasFactory, HasTranslations, SoftDeletes, LogsActivity;

    protected static function booted(): void
    {
        static::creating(function (Category $category): void {
            if (auth('admin')->check()) {
                $category->created_by_admin_id = auth('admin')->id();
            }
        });

        static::updating(function (Category $category): void {
            if (auth('admin')->check()) {
                $category->updated_by_admin_id = auth('admin')->id();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'translations',
        'parent_id', 'slug', 'icon', 'image', 'sort_order', 'is_active',
        'cover_image', 'banner_image', 'mobile_banner', 'is_featured',
        'publish_status', 'published_at', 'unpublished_at', 'is_locked',
        'created_by_admin_id', 'updated_by_admin_id',
    ];

    protected function casts(): array
    {
        return [
            'parent_id' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_locked' => 'boolean',
            'sort_order' => 'integer',
            'published_at' => 'datetime',
            'unpublished_at' => 'datetime',
        ];
    }

    public function createdByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by_admin_id');
    }

    public function updatedByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'updated_by_admin_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', true)
            ->where(function ($q) {
                $q->where('publish_status', 'Published')
                  ->orWhere(function ($q2) {
                      $q2->where('publish_status', 'Scheduled')
                         ->where('published_at', '<=', now());
                  });
            });
    }

    public function scopeRoot($query): void
    {
        $query->whereNull('parent_id');
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
