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
        Schema::table('customize_tour_requests', function (Blueprint $table): void {
            $table->foreignId('package_id')
                ->nullable()
                ->after('id')
                ->constrained('packages')
                ->nullOnDelete();

            $table->index('package_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customize_tour_requests', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('package_id');
        });
    }
};
