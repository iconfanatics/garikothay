<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'weight_value')) {
                $table->decimal('weight_value', 10, 2)->nullable()->after('weight_grams');
                $table->string('weight_unit', 10)->default('g')->after('weight_value');
            }
            if (!Schema::hasColumn('products', 'length')) {
                $table->decimal('length', 10, 2)->nullable()->after('weight_unit');
                $table->decimal('width', 10, 2)->nullable()->after('length');
                $table->decimal('height', 10, 2)->nullable()->after('width');
                $table->string('dimension_unit', 10)->default('cm')->after('height');
            }
        });

        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'weight_value')) {
                $table->decimal('weight_value', 10, 2)->nullable()->after('image_gallery');
                $table->string('weight_unit', 10)->default('g')->after('weight_value');
            }
            if (!Schema::hasColumn('product_variants', 'length')) {
                $table->decimal('length', 10, 2)->nullable()->after('weight_unit');
                $table->decimal('width', 10, 2)->nullable()->after('length');
                $table->decimal('height', 10, 2)->nullable()->after('width');
                $table->string('dimension_unit', 10)->default('cm')->after('height');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['weight_value', 'weight_unit', 'length', 'width', 'height', 'dimension_unit']);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['weight_value', 'weight_unit', 'length', 'width', 'height', 'dimension_unit']);
        });
    }
};
