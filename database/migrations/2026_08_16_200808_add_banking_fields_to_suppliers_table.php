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
        Schema::table('suppliers', function (Blueprint $table) {
            if (Schema::hasColumn('suppliers', 'bank_name')) {
                $table->dropColumn(['bank_name', 'account_name', 'account_number']);
            }
            $table->text('bank_details')->nullable();
            $table->string('personal_mobile_banking_provider')->nullable();
            $table->string('personal_mobile_banking_number')->nullable();
            $table->string('merchant_mobile_banking_provider')->nullable();
            $table->string('merchant_mobile_banking_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->dropColumn([
                'bank_details',
                'personal_mobile_banking_provider',
                'personal_mobile_banking_number',
                'merchant_mobile_banking_provider',
                'merchant_mobile_banking_number'
            ]);
        });
    }
};
