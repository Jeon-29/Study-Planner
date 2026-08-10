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
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            // Links the task to the logged-in user; cascade deletes tasks if a user account is removed
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Your form fields
            $table->string('title');
            $table->string('subject');
            $table->text('description')->nullable(); // Optional field
            $table->date('due_date');

            // Tracks state (pending, done, overdue)
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
