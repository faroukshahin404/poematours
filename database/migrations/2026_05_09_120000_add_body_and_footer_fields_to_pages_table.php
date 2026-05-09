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
        Schema::table('pages', function (Blueprint $table): void {
            $table->longText('body')->nullable()->after('og_tags');
            $table->boolean('show_in_footer')->default(false)->after('body');
            $table->string('footer_label')->nullable()->after('show_in_footer');
            $table->unsignedSmallInteger('footer_sort_order')->nullable()->after('footer_label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->dropColumn(['body', 'show_in_footer', 'footer_label', 'footer_sort_order']);
        });
    }
};
