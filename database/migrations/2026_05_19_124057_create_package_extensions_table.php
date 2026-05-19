<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_extensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
            $table->foreignId('extension_package_id')->constrained('packages')->cascadeOnDelete();
            $table->string('type', 32)->default('pre_tour');
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('inclusions_text')->nullable();
            $table->timestamps();

            $table->unique(['package_id', 'extension_package_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_extensions');
    }
};
