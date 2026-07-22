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
        \Illuminate\Support\Facades\DB::table('payment_gateways')->insertOrIgnore([
            [
                'name' => 'Cash on Delivery',
                'slug' => 'cod',
                'credentials' => null,
                'is_active' => true,
                'mode' => 'live',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SSLCommerz',
                'slug' => 'sslcommerz',
                'credentials' => json_encode(['store_id' => '', 'store_password' => '']),
                'is_active' => false,
                'mode' => 'sandbox',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'bKash',
                'slug' => 'bkash',
                'credentials' => json_encode(['app_key' => '', 'app_secret' => '', 'username' => '', 'password' => '']),
                'is_active' => false,
                'mode' => 'sandbox',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('payment_gateways')->whereIn('slug', ['cod', 'sslcommerz', 'bkash'])->delete();
    }
};
