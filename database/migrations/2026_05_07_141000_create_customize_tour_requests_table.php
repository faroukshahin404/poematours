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
        Schema::create('customize_tour_requests', function (Blueprint $table): void {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedSmallInteger('adults')->nullable();
            $table->unsignedSmallInteger('children')->nullable();
            $table->date('arrival_date')->nullable();
            $table->date('departure_date')->nullable();
            $table->unsignedSmallInteger('duration_days')->nullable();
            $table->string('destinations')->nullable();
            $table->json('interests')->nullable();
            $table->string('accommodation_style')->nullable();
            $table->string('budget_range')->nullable();
            $table->boolean('need_guide')->nullable();
            $table->boolean('need_transportation')->nullable();
            $table->string('preferred_contact_method')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('new');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customize_tour_requests');
    }
};
