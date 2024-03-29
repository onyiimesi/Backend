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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('business_name')->nullable();
            $table->string('location')->nullable();
            $table->string('ciso')->nullable();
            $table->string('siso')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->longText('industry')->nullable();
            $table->longText('about_business')->nullable();
            $table->string('website')->nullable();
            $table->string('business_service')->nullable();
            $table->string('business_email')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('company_type')->nullable();
            $table->string('social_media')->nullable();
            $table->string('social_media_two')->nullable();
            $table->string('password');
            $table->string('otp')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('verify_status')->nullable();
            $table->string('type')->nullable();
            $table->string('status');
            $table->string('terms')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
