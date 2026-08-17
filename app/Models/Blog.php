<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Blog extends Model
{
    use HasFactory, LogsActivity, HasTranslations;

    protected $fillable = [
        'blog_category_id',
        'slug',
        'featured_image',
        'author_id',
        'is_published',
        'published_at',
        'seo_title',
        'meta_description',
        'tags',
        'blog_code',
        'reading_time_minutes',
        'is_featured',
        'image_alt_text',
        'image_caption',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'tags' => 'array',
        'is_featured' => 'boolean',
        'reading_time_minutes' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($blog) {
            // Auto generate blog_code on creation
            if (empty($blog->blog_code)) {
                $lastBlog = static::orderBy('id', 'desc')->first();
                $suffix = 1;
                
                if ($lastBlog && preg_match('/BLG-(\d+)/', $lastBlog->blog_code, $matches)) {
                    $suffix = (int)$matches[1] + 1;
                } elseif ($lastBlog) {
                    $suffix = $lastBlog->id + 1;
                }
                
                do {
                    $code = 'BLG-' . str_pad((string)$suffix, 4, '0', STR_PAD_LEFT);
                    $suffix++;
                } while (static::where('blog_code', $code)->exists());
                
                $blog->blog_code = $code;
            }

            // Auto calculate reading time based on English content
            $content = '';
            if (!empty($blog->pendingTranslations) && isset($blog->pendingTranslations['en']['content'])) {
                $content = $blog->pendingTranslations['en']['content'];
            } else {
                $content = $blog->getTranslation('content', 'en') ?? '';
            }
            $wordCount = str_word_count(strip_tags((string)$content));
            $blog->reading_time_minutes = max(1, (int) ceil($wordCount / 200));
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }


    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'author_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(BlogTranslation::class);
    }

    public function scopePublished($query): void
    {
        $query->where('is_published', true)
              ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function getTitleAttribute(): ?string
    {
        return $this->getTranslation('title');
    }

    public function getExcerptAttribute(): ?string
    {
        return $this->getTranslation('excerpt');
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image
            ? Storage::disk('public')->url($this->featured_image)
            : null;
    }
}
