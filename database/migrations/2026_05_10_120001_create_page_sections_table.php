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
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('key');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('content')->nullable();
            $table->timestamps();

            $table->unique(['page_id', 'key']);
            $table->index(['page_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_sections');
    }
};
