<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_inclusions', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('icon')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('package_package_inclusion', function (Blueprint $table) {
            $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
            $table->foreignId('package_inclusion_id')->constrained('package_inclusions')->cascadeOnDelete();
            $table->primary(['package_id', 'package_inclusion_id']);
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->tinyInteger('is_private')->default(0)->after('recommended');
            $table->tinyInteger('is_small_group')->default(0)->after('is_private');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['is_private', 'is_small_group']);
        });

        Schema::dropIfExists('package_package_inclusion');
        Schema::dropIfExists('package_inclusions');
    }
};
