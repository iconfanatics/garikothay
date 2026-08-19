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
        Schema::table('shipping_zones', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->string('zone_type')->default('District')->after('description');
            $table->json('coverage_areas')->nullable()->after('zone_type');
            $table->boolean('is_cod_enabled')->default(true)->after('is_active');
        });

        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->boolean('free_shipping_enabled')->default(false)->after('base_charge');
            $table->boolean('is_cod_enabled')->default(true)->after('free_shipping_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_zones', function (Blueprint $table) {
            $table->dropColumn(['description', 'zone_type', 'coverage_areas', 'is_cod_enabled']);
        });

        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->dropColumn(['free_shipping_enabled', 'is_cod_enabled']);
        });
    }
};
