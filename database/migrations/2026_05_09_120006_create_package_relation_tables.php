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
        Schema::create('package_package_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('package_category_id')->constrained('package_categories')->cascadeOnUpdate()->restrictOnDelete();
            $table->unique(['package_id', 'package_category_id']);
        });

        Schema::create('package_package_label_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('package_label_group_id')->constrained('package_label_groups')->cascadeOnUpdate()->restrictOnDelete();
            $table->unique(['package_id', 'package_label_group_id'], 'pkg_label_group_unique');
        });

        Schema::create('package_package_label', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('package_label_id')->constrained('package_labels')->cascadeOnUpdate()->restrictOnDelete();
            $table->unique(['package_id', 'package_label_id']);
        });

        Schema::create('activity_package', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('activity_id')->constrained('activities')->cascadeOnUpdate()->restrictOnDelete();
            $table->unique(['package_id', 'activity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_package');
        Schema::dropIfExists('package_package_label');
        Schema::dropIfExists('package_package_label_group');
        Schema::dropIfExists('package_package_category');
    }
};
