<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DifficultyLevel;
use App\Enums\PlantType;
use App\Enums\SunlightRequirement;
use App\Enums\WateringFrequency;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, HasTranslations, SoftDeletes, LogsActivity;

    protected static function booted(): void
    {
        static::creating(function (Product $product): void {
            if (blank($product->sku)) {
                $product->sku = static::generateUniqueSku();
            }
            if (auth('admin')->check()) {
                $product->created_by_admin_id = auth('admin')->id();
            }
        });

        static::updating(function (Product $product): void {
            if (auth('admin')->check()) {
                $product->updated_by_admin_id = auth('admin')->id();
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
        'brand_id', 'unit_id', 'brand', 'category_id', 'slug', 'sku', 'barcode', 'price', 'compare_price',
        'cost_price', 'stock_quantity', 'reserved_stock', 'is_preorder', 'low_stock_threshold', 'weight_grams',
        'is_active', 'is_featured', 'is_new_arrival', 'requires_shipping',
        'shipping_restriction', 'has_special_handling', 'handling_type', 'is_free_shipping_eligible',
        'tax_rate', 'plant_type', 'sunlight', 'watering', 'difficulty', 'mature_size',
        'supplier_id', 'minimum_selling_price', 'supplier_stock_status',
        'supplier_stock_updated_at', 'product_source_url', 'supplier_product_code',
        'has_return_support', 'is_authentic_product', 'supplier_shipping_charge',
        'supplier_delivery_time', 'supplier_delivery_partner', 'warranty_type',
        'warranty_duration', 'warranty_claim_process', 'internal_notes',
        'publish_status', 'published_at', 'unpublished_at', 'discount_type',
        'discount_amount', 'discount_start_date', 'discount_end_date',
        'scheduled_price', 'price_effective_date', 'documents', 'faqs',
        'created_by_admin_id', 'updated_by_admin_id',
        'product_type', 'features', 'custom_fields', 'collections', 'highlights', 'certifications', 'video_url',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'minimum_selling_price' => 'decimal:2',
            'supplier_shipping_charge' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'supplier_stock_updated_at' => 'date',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_preorder' => 'boolean',
            'requires_shipping' => 'boolean',
            'has_special_handling' => 'boolean',
            'is_free_shipping_eligible' => 'boolean',
            'has_return_support' => 'boolean',
            'is_authentic_product' => 'boolean',
            'plant_type' => PlantType::class,
            'sunlight' => SunlightRequirement::class,
            'watering' => WateringFrequency::class,
            'difficulty' => DifficultyLevel::class,
            'published_at' => 'datetime',
            'unpublished_at' => 'datetime',
            'discount_start_date' => 'datetime',
            'discount_end_date' => 'datetime',
            'price_effective_date' => 'datetime',
            'discount_amount' => 'decimal:2',
            'scheduled_price' => 'decimal:2',
            'documents' => 'array',
            'faqs' => 'array',
            'features' => 'array',
            'custom_fields' => 'array',
            'collections' => 'array',
            'highlights' => 'array',
            'certifications' => 'array',
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

public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function tags()
    {
        return $this->belongsToMany(ProductTag::class, 'product_product_tag');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', true);
    }

    public function scopeFeatured($query): void
    {
        $query->where('is_featured', true);
    }

    public function scopeNewArrivals($query): void
    {
        $query->where('is_new_arrival', true);
    }

    public function scopeInStock($query): void
    {
        $query->where('stock_quantity', '>', 0);
    }

    public function scopeLowStock($query): void
    {
        $query->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
              ->where('stock_quantity', '>', 0);
    }

    public function getNameAttribute(): ?string
    {
        return $this->getTranslation('name');
    }

    public function getShortDescriptionAttribute(): ?string
    {
        return $this->getTranslation('short_description');
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->getTranslation('description');
    }

    public function getSpecificationsAttribute(): ?string
    {
        return $this->getTranslation('specifications');
    }

    public function getShippingReturnsAttribute(): ?string
    {
        return $this->getTranslation('shipping_returns');
    }

    public function getPrimaryImageAttribute(): ?ProductImage
    {
        return $this->images->firstWhere('is_primary', true) ?? $this->images->first();
    }

    public function getFormattedPriceAttribute(): string
    {
        return '৳' . number_format((float) $this->price, 2);
    }

    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->compare_price || $this->compare_price <= $this->price) {
            return 0;
        }

        return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }

    public function getProfitMarginAttribute(): ?float
    {
        if ($this->cost_price === null) {
            return null;
        }

        return round((float) $this->price - (float) $this->cost_price, 2);
    }

    public function getProfitMarginPercentageAttribute(): ?float
    {
        if ($this->cost_price === null || (float) $this->price <= 0) {
            return null;
        }

        return round(($this->profit_margin / (float) $this->price) * 100, 2);
    }

    public function getAverageRatingAttribute(): float
    {
        if (array_key_exists('reviews_avg_rating', $this->attributes)) {
            return round((float) ($this->attributes['reviews_avg_rating'] ?? 0), 1);
        }
        return round($this->reviews()->where('is_approved', true)->avg('rating') ?? 0, 1);
    }

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    /** @param array<int, string> $paths */
    public function syncImages(array $paths): void
    {
        $paths = collect($paths)
            ->filter(fn ($path) => is_string($path) && $path !== '')
            ->unique()
            ->values();

        $existingImages = $this->images()->get();
        $removedPaths = $existingImages->pluck('path')->diff($paths);

        if ($removedPaths->isNotEmpty()) {
            Storage::disk('public')->delete($removedPaths->all());
            $this->images()->whereIn('path', $removedPaths)->delete();
        }

        foreach ($paths as $sortOrder => $path) {
            $this->images()->updateOrCreate(
                ['path' => $path],
                [
                    'sort_order' => $sortOrder,
                    'is_primary' => $sortOrder === 0,
                ],
            );
        }

        $this->images()
            ->whereNotIn('path', $paths->all())
            ->update(['is_primary' => false]);

        $this->unsetRelation('images');
    }

    public static function generateUniqueSku(): string
    {
        do {
            $sku = 'GK-' . Str::random(6);
        } while (static::withTrashed()->where('sku', $sku)->exists());

        return $sku;
    }
}
