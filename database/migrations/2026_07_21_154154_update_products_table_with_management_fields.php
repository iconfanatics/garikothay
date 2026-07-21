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
            $table->string('publish_status')->default('Published')->after('is_active');
            $table->timestamp('published_at')->nullable()->after('publish_status');
            $table->timestamp('unpublished_at')->nullable()->after('published_at');
            $table->string('discount_type')->nullable()->after('compare_price');
            $table->decimal('discount_amount', 10, 2)->nullable()->after('discount_type');
            $table->timestamp('discount_start_date')->nullable()->after('discount_amount');
            $table->timestamp('discount_end_date')->nullable()->after('discount_start_date');
            $table->decimal('scheduled_price', 10, 2)->nullable()->after('price');
            $table->timestamp('price_effective_date')->nullable()->after('scheduled_price');
            $table->json('documents')->nullable()->after('internal_notes');
            $table->foreignId('created_by_admin_id')->nullable()->constrained('admins')->nullOnDelete()->after('supplier_id');
            $table->foreignId('updated_by_admin_id')->nullable()->constrained('admins')->nullOnDelete()->after('created_by_admin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['created_by_admin_id']);
            $table->dropForeign(['updated_by_admin_id']);
            $table->dropColumn([
                'publish_status',
                'published_at',
                'unpublished_at',
                'discount_type',
                'discount_amount',
                'discount_start_date',
                'discount_end_date',
                'scheduled_price',
                'price_effective_date',
                'documents',
                'created_by_admin_id',
                'updated_by_admin_id',
            ]);
        });
    }
};
