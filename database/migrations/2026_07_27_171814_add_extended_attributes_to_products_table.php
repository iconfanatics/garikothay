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
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_type')->nullable()->after('is_active');
            $table->json('features')->nullable()->after('product_type');
            $table->json('custom_fields')->nullable()->after('features');
            $table->json('collections')->nullable()->after('custom_fields');
            $table->json('highlights')->nullable()->after('collections');
            $table->json('certifications')->nullable()->after('highlights');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'product_type',
                'features',
                'custom_fields',
                'collections',
                'highlights',
                'certifications',
            ]);
        });
    }
};
