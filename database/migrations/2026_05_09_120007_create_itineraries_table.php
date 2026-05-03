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
        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('breakfast')->default(false);
            $table->boolean('lunch')->default(false);
            $table->boolean('dinner')->default(false);
            $table->boolean('snacks')->default(false);
            $table->foreignId('destination_id')->constrained('destinations')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('hotel_id')->nullable()->constrained('hotels')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('boat_id')->nullable()->constrained('boats')->cascadeOnUpdate()->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itineraries');
    }
};
