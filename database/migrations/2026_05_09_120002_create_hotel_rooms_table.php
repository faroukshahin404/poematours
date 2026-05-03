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
        Schema::create('hotel_rooms', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('slug');
            $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedInteger('capacity');
            $table->decimal('area', 10, 2)->nullable();
            $table->decimal('base_price', 12, 2);
            $table->decimal('single_supplement', 12, 2)->default(0);
            $table->foreignId('created_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();

            $table->unique(['hotel_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_rooms');
    }
};
