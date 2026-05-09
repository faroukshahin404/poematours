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
        Schema::create('crm_contact_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crm_contact_id')->constrained('crm_contacts')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('crm_service_id')->constrained('crm_services')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['crm_contact_id', 'crm_service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_contact_service');
    }
};
