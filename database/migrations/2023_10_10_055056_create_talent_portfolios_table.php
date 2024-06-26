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
        Schema::create('talent_portfolios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('talent_id');
            $table->string('title');
            $table->string('category_id');
            $table->string('job_type');
            $table->string('location');
            $table->string('max_rate');
            $table->string('link');
            $table->json('tags');
            $table->longText('cover_image');
            $table->longText('body');
            $table->enum('is_draft', ['true', 'false']);

            $table->foreign('talent_id')->references('id')->on('talent')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talent_portfolios');
    }
};
