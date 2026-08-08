<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('alternative_contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('company_name')->nullable();
            $table->string('website')->nullable();
            
            $table->string('business_type')->nullable();
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('trade_license_no')->nullable();
            $table->string('bin_vat_no')->nullable();
            $table->string('visiting_card_image')->nullable();
            
            $table->string('payment_terms')->nullable();
            $table->integer('minimum_order_quantity')->nullable();
            
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('mobile_banking_provider')->nullable();
            $table->string('mobile_banking_number')->nullable();
            
            $table->boolean('preferred_supplier')->default(false);
            $table->string('supplier_code')->unique()->nullable();
            $table->text('notes')->nullable();
            
            $table->string('whatsapp_number')->nullable();
            $table->string('facebook_page')->nullable();
            
            $table->text('pickup_address')->nullable();
            $table->string('delivery_coverage')->nullable();
            $table->string('preferred_courier')->nullable();
            
            $table->boolean('supports_return')->default(false);
            $table->boolean('warranty_support')->default(false);
            $table->integer('average_delivery_time_days')->nullable();
            
            $table->foreignId('account_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('last_contact_date')->nullable();
            $table->date('last_purchase_date')->nullable();
            $table->text('remarks')->nullable();

            $table->decimal('total_purchase_amount', 15, 2)->default(0);
            $table->decimal('outstanding_due', 15, 2)->default(0);
            $table->integer('total_purchase_orders')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropForeign(['account_manager_id']);
            $table->dropColumn([
                'alternative_contact_number', 'email', 'company_name', 'website',
                'business_type', 'division', 'district', 'postal_code', 'trade_license_no', 'bin_vat_no', 'visiting_card_image',
                'payment_terms', 'minimum_order_quantity',
                'bank_name', 'account_name', 'account_number', 'mobile_banking_provider', 'mobile_banking_number',
                'preferred_supplier', 'supplier_code', 'notes',
                'whatsapp_number', 'facebook_page',
                'pickup_address', 'delivery_coverage', 'preferred_courier',
                'supports_return', 'warranty_support', 'average_delivery_time_days',
                'account_manager_id', 'last_contact_date', 'last_purchase_date', 'remarks',
                'total_purchase_amount', 'outstanding_due', 'total_purchase_orders'
            ]);
        });
    }
};
