<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_travellers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('first_name_on_passport')->nullable();
            $table->string('middle_name_on_passport')->nullable();
            $table->string('last_name_on_passport')->nullable();
            $table->string('gender')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('passport_country')->nullable();
            $table->string('passport_number')->nullable();
            $table->date('passport_expiration_date')->nullable();
            $table->string('country_of_birth')->nullable();
            $table->string('father_first_name')->nullable();
            $table->string('passport_photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_travellers');
    }
};
