<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->string('barcode')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable()->comment('Original price for showing discount');
            $table->decimal('cost_price', 10, 2)->nullable()->comment('Internal cost for profit calculation');
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->unsignedInteger('low_stock_threshold')->default(5);
            $table->unsignedInteger('weight_grams')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new_arrival')->default(false);
            $table->boolean('requires_shipping')->default(true);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->enum('plant_type', ['indoor', 'outdoor', 'both', 'not_plant'])->nullable();
            $table->enum('sunlight', ['full_sun', 'partial_shade', 'full_shade', 'any'])->nullable();
            $table->enum('watering', ['daily', 'weekly', 'biweekly', 'monthly'])->nullable();
            $table->enum('difficulty', ['beginner', 'intermediate', 'expert'])->nullable();
            $table->string('mature_size')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index('sku');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('category_id');
        });

        Schema::create('product_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('name');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->text('care_instructions')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->unique(['product_id', 'locale']);
            $table->index(['product_id', 'locale']);
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('alt_text')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->index('product_id');
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('sku')->unique();
            $table->decimal('price_modifier', 8, 2)->default(0)->comment('Added to product base price, can be negative');
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_translations');
        Schema::dropIfExists('products');
    }
};
