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
        Schema::table('product_variants', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable()->after('sku');
            $table->decimal('compare_price', 10, 2)->nullable()->after('price');
            $table->json('image_gallery')->nullable()->after('stock_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['price', 'compare_price', 'image_gallery']);
        });
    }
};
