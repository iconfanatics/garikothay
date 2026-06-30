<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('preferred_payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('last_login_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'division',
                'district',
                'preferred_payment_method',
                'notes',
                'last_login_at',
            ]);
        });
    }
};
