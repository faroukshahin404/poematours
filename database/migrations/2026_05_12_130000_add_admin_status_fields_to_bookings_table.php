<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->string('booking_status')->default('pending')->after('flight_other_text');
            $table->string('payment_status')->default('not_paid')->after('booking_status');
            $table->decimal('paid_amount', 12, 2)->default(0)->after('payment_status');
            $table->decimal('total_amount', 12, 2)->nullable()->after('paid_amount');

            $table->index('booking_status');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->dropIndex(['booking_status']);
            $table->dropIndex(['payment_status']);
            $table->dropColumn(['booking_status', 'payment_status', 'paid_amount', 'total_amount']);
        });
    }
};
