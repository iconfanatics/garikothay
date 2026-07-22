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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('cover_image')->nullable()->after('image');
            $table->string('banner_image')->nullable()->after('cover_image');
            $table->string('mobile_banner')->nullable()->after('banner_image');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->string('publish_status')->default('Published')->after('is_featured');
            $table->timestamp('published_at')->nullable()->after('publish_status');
            $table->timestamp('unpublished_at')->nullable()->after('published_at');
            $table->boolean('is_locked')->default(false)->after('unpublished_at');
            $table->foreignId('created_by_admin_id')->nullable()->constrained('admins')->nullOnDelete()->after('is_locked');
            $table->foreignId('updated_by_admin_id')->nullable()->constrained('admins')->nullOnDelete()->after('created_by_admin_id');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['created_by_admin_id']);
            $table->dropForeign(['updated_by_admin_id']);
            $table->dropColumn([
                'cover_image',
                'banner_image',
                'mobile_banner',
                'is_featured',
                'publish_status',
                'published_at',
                'unpublished_at',
                'is_locked',
                'created_by_admin_id',
                'updated_by_admin_id',
            ]);
            $table->dropSoftDeletes();
        });
    }
};
