<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->foreignId('destination_id')
                ->nullable()
                ->after('slug')
                ->constrained('destinations')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });

        $firstDestinationId = DB::table('destinations')->orderBy('id')->value('id');

        if ($firstDestinationId !== null) {
            DB::table('activities')
                ->whereNull('destination_id')
                ->update(['destination_id' => $firstDestinationId]);
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropConstrainedForeignId('destination_id');
        });
    }
};
