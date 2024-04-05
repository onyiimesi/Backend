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
        if (!Schema::hasColumn('question_answers', 'job_id')) {
            Schema::table('question_answers', function (Blueprint $table) {
                $table->unsignedBigInteger('job_id')->nullable();
            });
        }
    }
};
