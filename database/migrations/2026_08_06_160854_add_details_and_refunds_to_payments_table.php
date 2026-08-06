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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_reference')->nullable()->after('transaction_id');
            $table->string('gateway_response_code')->nullable()->after('gateway_response');
            $table->text('gateway_response_message')->nullable()->after('gateway_response_code');
            $table->decimal('refund_amount', 10, 2)->nullable()->after('gateway_response_message');
            $table->timestamp('refund_date')->nullable()->after('refund_amount');
            $table->string('refund_transaction_id')->nullable()->after('refund_date');
            $table->text('refund_reason')->nullable()->after('refund_transaction_id');
            $table->text('remarks')->nullable()->after('refund_reason');
            $table->foreignId('created_by_admin_id')->nullable()->constrained('admins')->nullOnDelete()->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['created_by_admin_id']);
            $table->dropColumn([
                'payment_reference',
                'gateway_response_code',
                'gateway_response_message',
                'refund_amount',
                'refund_date',
                'refund_transaction_id',
                'refund_reason',
                'remarks',
                'created_by_admin_id',
            ]);
        });
    }
};
