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
        // Add notes, evaluation, follow_up to parent_consultations
        Schema::table('parent_consultations', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('topic');
            $table->text('evaluation')->nullable()->after('notes');
            $table->text('follow_up')->nullable()->after('evaluation');
        });

        // Create counseling_documents table for photo documentation
        Schema::create('counseling_documents', function (Blueprint $table) {
            $table->id();
            $table->morphs('counseling');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->unsignedBigInteger('file_size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parent_consultations', function (Blueprint $table) {
            $table->dropColumn(['notes', 'evaluation', 'follow_up']);
        });

        Schema::dropIfExists('counseling_documents');
    }
};
