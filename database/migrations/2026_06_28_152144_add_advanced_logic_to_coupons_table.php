<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->enum('applicable_type', ['all', 'products', 'categories'])->default('all')->after('type');
            $table->boolean('is_first_order_only')->default(false)->after('max_discount_amount');
            $table->unsignedInteger('per_customer_limit')->nullable()->after('is_first_order_only');
        });

        Schema::create('category_coupon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('coupon_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_product');
        Schema::dropIfExists('category_coupon');

        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['applicable_type', 'is_first_order_only', 'per_customer_limit']);
        });
    }
};
