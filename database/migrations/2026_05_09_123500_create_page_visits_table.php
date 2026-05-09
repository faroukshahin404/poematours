<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_visits', function (Blueprint $table): void {
            $table->id();
            $table->string('route_name')->nullable();
            $table->string('path', 2048);
            $table->string('country_code', 2)->nullable();
            $table->string('country_name')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('visited_at');
            $table->timestamps();

            $table->index('route_name');
            $table->index('country_code');
            $table->index('visited_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_visits');
    }
};
