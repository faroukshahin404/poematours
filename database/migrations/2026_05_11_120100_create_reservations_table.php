<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('preferred_contact_method')->nullable();
            $table->unsignedSmallInteger('adults')->default(1);
            $table->unsignedSmallInteger('children')->default(0);
            $table->date('arrival_date')->nullable();
            $table->date('departure_date')->nullable();
            $table->unsignedSmallInteger('duration_days')->nullable();
            $table->string('destinations')->nullable();
            $table->string('tour_style')->nullable();
            $table->string('hotel_category')->nullable();
            $table->boolean('need_transfers')->default(false);
            $table->boolean('need_domestic_flights')->default(false);
            $table->json('selected_addons')->nullable();
            $table->json('addons_breakdown')->nullable();
            $table->unsignedBigInteger('addons_total')->default(0);
            $table->unsignedBigInteger('base_total')->default(0);
            $table->unsignedBigInteger('estimated_total')->default(0);
            $table->unsignedSmallInteger('deposit_percentage')->default(20);
            $table->unsignedBigInteger('deposit_amount')->default(0);
            $table->string('currency', 3)->default('USD');
            $table->string('payment_status')->default('pending');
            $table->string('status')->default('new');
            $table->string('stripe_payment_intent_id')->nullable()->index();
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('payment_status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
