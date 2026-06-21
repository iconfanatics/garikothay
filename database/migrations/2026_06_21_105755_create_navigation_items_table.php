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
        Schema::create('navigation_items', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('top_nav');
            $table->string('url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('group');
            $table->index('is_active');
            $table->index('sort_order');
        });

        Schema::create('navigation_item_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('navigation_item_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('label');

            $table->unique(['navigation_item_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navigation_item_translations');
        Schema::dropIfExists('navigation_items');
    }
};
