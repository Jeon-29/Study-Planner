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
        Schema::create('assessments', function (Blueprint $table) {
        $table->id();
        // Foreign keys to link to the user and the specific subject
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('subject_id')->constrained()->cascadeOnDelete();

        // Core details
        $table->string('title'); // e.g., "Midterm Exam" or "Chapter 3 Quiz"
        $table->enum('type', ['quiz', 'exam']);
        $table->enum('status', ['upcoming', 'finished', 'overdue'])->default('upcoming');

        // Logistics
        $table->date('assessment_date');
        $table->time('start_time')->nullable();
        $table->string('room')->nullable();

        // Grading
        $table->integer('total_items')->default(0);
        $table->integer('score')->nullable(); // Nullable because you won't have a score while it's 'upcoming'

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
