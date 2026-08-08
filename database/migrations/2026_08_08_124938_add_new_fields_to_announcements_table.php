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
        Schema::table('announcements', function (Blueprint $table) {
            $table->text('summary')->nullable()->after('content');
            $table->string('display_location')->default('site_wide')->after('summary');
            $table->integer('priority')->default(0)->after('display_location');
            $table->string('button_text')->nullable()->after('priority');
            $table->string('button_url')->nullable()->after('button_text');
            $table->boolean('open_in_new_tab')->default(false)->after('button_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn([
                'summary',
                'display_location',
                'priority',
                'button_text',
                'button_url',
                'open_in_new_tab',
            ]);
        });
    }
};
