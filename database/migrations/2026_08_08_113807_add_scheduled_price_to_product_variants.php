<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->decimal('scheduled_price', 10, 2)->nullable()->after('price');
            $table->timestamp('price_effective_date')->nullable()->after('scheduled_price');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['scheduled_price', 'price_effective_date']);
        });
    }
};
