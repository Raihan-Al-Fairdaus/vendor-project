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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();

            // Business Identity
            $table->string('company_name');
            $table->string('business_category');
            $table->text('company_address');
            $table->string('npwp');

            // Contact Information
            $table->string('company_email')->unique();
            $table->string('company_phone');
            $table->string('pic_name');

            // Verification Documents
            $table->string('id_card_path');
            $table->string('company_photos_path')->nullable();

            // Registration Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};