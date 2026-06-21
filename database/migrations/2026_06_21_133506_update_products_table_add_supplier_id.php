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
            $table->foreignId('supplier_id')->nullable()->after('category_id')->constrained('suppliers')->nullOnDelete();
            
            // Drop old string columns
            $table->dropColumn([
                'supplier_name',
                'supplier_contact_person',
                'supplier_contact_number',
                'supplier_address'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');
            
            $table->string('supplier_name')->nullable()->after('barcode');
            $table->string('supplier_contact_person')->nullable()->after('supplier_name');
            $table->string('supplier_contact_number', 30)->nullable()->after('supplier_contact_person');
            $table->text('supplier_address')->nullable()->after('supplier_contact_number');
        });
    }
};
