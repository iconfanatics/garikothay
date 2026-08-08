<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('blog_code')->unique()->nullable()->after('id');
            $table->integer('reading_time_minutes')->nullable()->after('author_id');
            $table->boolean('is_featured')->default(false)->after('is_published');
            $table->string('image_alt_text')->nullable()->after('featured_image');
            $table->string('image_caption')->nullable()->after('image_alt_text');
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn([
                'blog_code',
                'reading_time_minutes',
                'is_featured',
                'image_alt_text',
                'image_caption',
            ]);
        });
    }
};
