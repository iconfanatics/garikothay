<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->string('supplier_name')->nullable()->after('barcode');
            $table->string('supplier_contact_person')->nullable()->after('supplier_name');
            $table->string('supplier_contact_number', 30)->nullable()->after('supplier_contact_person');
            $table->text('supplier_address')->nullable()->after('supplier_contact_number');

            $table->decimal('minimum_selling_price', 10, 2)->nullable()->after('cost_price');
            $table->string('supplier_stock_status', 30)->nullable()->after('low_stock_threshold');
            $table->date('supplier_stock_updated_at')->nullable()->after('supplier_stock_status');

            $table->string('product_source_url', 2048)->nullable()->after('supplier_stock_updated_at');
            $table->string('supplier_product_code')->nullable()->after('product_source_url');
            $table->boolean('has_return_support')->default(false)->after('supplier_product_code');
            $table->boolean('is_authentic_product')->default(false)->after('has_return_support');

            $table->decimal('supplier_shipping_charge', 10, 2)->nullable()->after('weight_grams');
            $table->string('supplier_delivery_time')->nullable()->after('supplier_shipping_charge');
            $table->string('supplier_delivery_partner')->nullable()->after('supplier_delivery_time');

            $table->string('warranty_type', 30)->nullable()->after('supplier_delivery_partner');
            $table->string('warranty_duration')->nullable()->after('warranty_type');
            $table->text('warranty_claim_process')->nullable()->after('warranty_duration');
            $table->longText('internal_notes')->nullable()->after('warranty_claim_process');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn([
                'supplier_name',
                'supplier_contact_person',
                'supplier_contact_number',
                'supplier_address',
                'minimum_selling_price',
                'supplier_stock_status',
                'supplier_stock_updated_at',
                'product_source_url',
                'supplier_product_code',
                'has_return_support',
                'is_authentic_product',
                'supplier_shipping_charge',
                'supplier_delivery_time',
                'supplier_delivery_partner',
                'warranty_type',
                'warranty_duration',
                'warranty_claim_process',
                'internal_notes',
            ]);
        });
    }
};
