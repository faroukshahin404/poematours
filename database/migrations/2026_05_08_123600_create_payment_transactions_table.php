<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table): void {
            $table->id();
            $table->string('payment_key', 64)->unique();
            $table->string('stripe_session_id')->unique();
            $table->string('currency', 3);
            $table->unsignedBigInteger('total_amount_minor');
            $table->unsignedBigInteger('paid_amount_minor')->default(0);
            $table->unsignedBigInteger('charge_amount_minor');
            $table->string('status', 40)->default('unpaid');
            $table->string('payment_link')->nullable();
            $table->json('client_info')->nullable();
            $table->json('gateway_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
