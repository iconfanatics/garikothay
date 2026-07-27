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
        Schema::table("products", function (Blueprint $table) {
            $table->foreignId("brand_id")->nullable()->constrained()->nullOnDelete();
            $table->foreignId("unit_id")->nullable()->constrained()->nullOnDelete();
        });

        Schema::create("product_product_tag", function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_id")->constrained("products")->cascadeOnDelete();
            $table->foreignId("product_tag_id")->constrained("product_tags")->cascadeOnDelete();
            $table->unique(["product_id", "product_tag_id"]);
        });

        Schema::table("product_variants", function (Blueprint $table) {
            $table->foreignId("variant_type_id")->nullable()->constrained("variant_types")->nullOnDelete();
            $table->foreignId("variant_value_id")->nullable()->constrained("variant_values")->nullOnDelete();
            $table->string("name")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("product_variants", function (Blueprint $table) {
            $table->dropForeign(["variant_type_id"]);
            $table->dropForeign(["variant_value_id"]);
            $table->dropColumn(["variant_type_id", "variant_value_id"]);
            // Note: Cannot easily revert name to nullable(false) in SQLite without full rebuild, so we leave it nullable.
        });

        Schema::dropIfExists("product_product_tag");

        Schema::table("products", function (Blueprint $table) {
            $table->dropForeign(["brand_id"]);
            $table->dropForeign(["unit_id"]);
            $table->dropColumn(["brand_id", "unit_id"]);
        });
    }
};
