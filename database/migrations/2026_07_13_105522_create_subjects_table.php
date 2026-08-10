<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            // Connects the subject directly to the user who created it
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('code', 20);        // e.g., "IT211"
            $table->string('name', 150);       // e.g., "Web Development"
            $table->string('color_theme')->default('blue'); // For glassmorphism pastel accents
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
