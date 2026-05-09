<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table): void {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->json('traveler_emails')->nullable();
            $table->string('contact_phone_number')->nullable();
            $table->string('itinerary_cover_name')->nullable();
            $table->string('mailing_street')->nullable();
            $table->string('mailing_street_line_2')->nullable();
            $table->string('mailing_city')->nullable();
            $table->string('mailing_state')->nullable();
            $table->string('mailing_zip_code')->nullable();
            $table->string('mailing_country')->nullable();
            $table->json('mobility_concerns')->nullable();
            $table->text('dietary_restrictions')->nullable();
            $table->text('other_needs_or_requests')->nullable();
            $table->json('dynamic_answers')->nullable();
            $table->unsignedInteger('travellers_count')->default(0);
            $table->string('flight_option')->nullable();
            $table->date('arrival_flight_date')->nullable();
            $table->time('arrival_flight_time')->nullable();
            $table->string('arrival_flight_airline')->nullable();
            $table->string('arrival_flight_number')->nullable();
            $table->date('return_flight_date')->nullable();
            $table->time('return_flight_departure_time')->nullable();
            $table->string('return_flight_airline')->nullable();
            $table->string('return_flight_number')->nullable();
            $table->text('flight_other_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
