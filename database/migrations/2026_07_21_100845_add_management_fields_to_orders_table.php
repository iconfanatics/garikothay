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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('assigned_staff_id')->nullable()->constrained('admins')->nullOnDelete()->after('user_id');
            $table->string('order_source')->default('Website')->after('status');
            $table->string('customer_type')->default('Retail')->after('order_source');
            $table->boolean('is_fraud')->default(false)->after('total');
            $table->string('delivery_method')->nullable()->after('payment_method');
            $table->string('tracking_number')->nullable()->after('delivery_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['assigned_staff_id']);
            $table->dropColumn([
                'assigned_staff_id',
                'order_source',
                'customer_type',
                'is_fraud',
                'delivery_method',
                'tracking_number',
            ]);
        });
    }
};
