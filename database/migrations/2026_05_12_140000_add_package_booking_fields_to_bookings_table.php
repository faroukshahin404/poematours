<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->foreignId('package_id')->nullable()->after('id')->constrained('packages')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('package_date_price_id')->nullable()->after('package_id')->constrained('package_date_prices')->cascadeOnUpdate()->nullOnDelete();
            $table->decimal('unit_price', 12, 2)->nullable()->after('package_date_price_id');
            $table->string('booking_source')->default('general')->after('unit_price');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('package_id');
            $table->dropConstrainedForeignId('package_date_price_id');
            $table->dropColumn(['unit_price', 'booking_source']);
        });
    }
};
