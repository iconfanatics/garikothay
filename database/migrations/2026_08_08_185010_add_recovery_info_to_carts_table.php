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
        Schema::table('carts', function (Blueprint $table) {
            $table->string('recovery_status')->default('Pending');
            $table->boolean('is_reminder_sent')->default(false);
            $table->timestamp('reminder_sent_at')->nullable();
            $table->decimal('shipping_charge', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn([
                'recovery_status',
                'is_reminder_sent',
                'reminder_sent_at',
                'shipping_charge',
                'discount_amount',
                'grand_total'
            ]);
        });
    }
};
