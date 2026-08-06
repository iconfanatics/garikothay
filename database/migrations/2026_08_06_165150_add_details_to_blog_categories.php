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
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('slug');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->integer('sort_order')->default(0)->after('meta_description');
            $table->boolean('is_featured')->default(false)->after('sort_order');
            $table->boolean('show_on_homepage')->default(false)->after('is_featured');
        });

        Schema::table('blog_category_translations', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_category_translations', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('blog_categories', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title', 'meta_description', 'sort_order', 'is_featured', 'show_on_homepage'
            ]);
        });
    }
};
