<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_visits', function (Blueprint $table): void {
            $table->json('route_parameters')->nullable()->after('path');
            $table->string('package_slug')->nullable()->after('route_parameters');

            $table->index('package_slug');
        });
    }

    public function down(): void
    {
        Schema::table('page_visits', function (Blueprint $table): void {
            $table->dropIndex(['package_slug']);
            $table->dropColumn(['route_parameters', 'package_slug']);
        });
    }
};
