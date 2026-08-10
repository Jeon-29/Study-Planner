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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            // Links the file to a specific subject, deletes files if the subject is removed
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('category'); // e.g., Lecture, Presentation, Syllabus
            $table->string('path');     // Storage file path
            $table->string('filename'); // Original uploaded filename
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
