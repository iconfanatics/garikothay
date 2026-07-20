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
            $table->enum('shipping_restriction', ['home_delivery', 'pickup_only', 'courier_restricted'])->default('home_delivery')->after('requires_shipping');
            $table->boolean('has_special_handling')->default(false)->after('shipping_restriction');
            $table->enum('handling_type', ['fragile', 'liquid', 'battery', 'hazardous'])->nullable()->after('has_special_handling');
            $table->boolean('is_free_shipping_eligible')->default(false)->after('handling_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_restriction',
                'has_special_handling',
                'handling_type',
                'is_free_shipping_eligible',
            ]);
        });
    }
};
