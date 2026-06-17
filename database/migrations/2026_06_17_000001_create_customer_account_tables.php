<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->string('service_type');
            $table->string('provider')->nullable();
            $table->date('booking_date')->nullable();
            $table->string('location')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        Schema::create('user_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->string('title');
            $table->enum('type', ['product', 'garage', 'driver', 'carwash', 'rental']);
            $table->decimal('price', 10, 2)->nullable();
            $table->string('location')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->enum('status', ['active', 'paused', 'sold'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_listings');
        Schema::dropIfExists('service_bookings');
    }
};
