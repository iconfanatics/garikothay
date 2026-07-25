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
        Schema::table('product_translations', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
        });
        Schema::table('category_translations', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
        });
        Schema::table('page_translations', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
        });
        Schema::table('navigation_item_translations', function (Blueprint $table) {
            $table->string('label')->nullable()->change();
        });
        Schema::table('blog_category_translations', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
        });
        Schema::table('blog_translations', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
        });
        Schema::table('banner_translations', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_translations', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
        });
        Schema::table('category_translations', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
        });
        Schema::table('page_translations', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
        });
        Schema::table('navigation_item_translations', function (Blueprint $table) {
            $table->string('label')->nullable(false)->change();
        });
        Schema::table('blog_category_translations', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
        });
        Schema::table('blog_translations', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
        });
        Schema::table('banner_translations', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
        });
    }
};
