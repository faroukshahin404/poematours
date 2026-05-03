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
        Schema::create('package_date_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('from_date');
            $table->date('to_date');
            $table->unsignedInteger('available_seats')->default(0);
            $table->decimal('price', 12, 2);
            $table->decimal('offer', 12, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('package_date_price_accommodations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_date_price_id')->constrained('package_date_prices')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('room_id')->constrained('hotel_rooms')->cascadeOnUpdate()->restrictOnDelete();
            $table->unique(['package_date_price_id', 'hotel_id', 'room_id'], 'pkg_date_acc_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_date_price_accommodations');
        Schema::dropIfExists('package_date_prices');
    }
};
