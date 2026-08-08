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
        Schema::table('invoices', function (Blueprint $table) {
            $table->date('due_date')->nullable()->after('invoice_date');
            $table->string('payment_status')->nullable()->after('status');
            $table->decimal('subtotal', 10, 2)->default(0)->after('payment_status');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('subtotal');
            $table->decimal('shipping_amount', 10, 2)->default(0)->after('discount_amount');
            $table->decimal('tax_amount', 10, 2)->default(0)->after('shipping_amount');
            $table->decimal('total', 10, 2)->default(0)->after('tax_amount');
            $table->decimal('paid_amount', 10, 2)->default(0)->after('total');
            $table->decimal('due_amount', 10, 2)->default(0)->after('paid_amount');
            $table->string('payment_method')->nullable()->after('due_amount');
            $table->string('transaction_id')->nullable()->after('payment_method');
            $table->text('customer_note')->nullable()->after('transaction_id');
            $table->text('admin_note')->nullable()->after('customer_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'due_date',
                'payment_status',
                'subtotal',
                'discount_amount',
                'shipping_amount',
                'tax_amount',
                'total',
                'paid_amount',
                'due_amount',
                'payment_method',
                'transaction_id',
                'customer_note',
                'admin_note',
            ]);
        });
    }
};
