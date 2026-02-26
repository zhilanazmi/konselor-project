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
        Schema::create('individual_counselings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('counselor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->dateTime('scheduled_at')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'followed_up'])->default('scheduled');
            $table->enum('category', ['pribadi', 'sosial', 'belajar', 'karir'])->default('pribadi');
            $table->text('problem_description')->nullable();
            $table->text('approach')->nullable();
            $table->text('result')->nullable();
            $table->text('follow_up_plan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individual_counselings');
    }
};
