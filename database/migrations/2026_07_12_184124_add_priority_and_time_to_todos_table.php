<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            // Adds priority flags with a low fallback option structure
            $table->string('priority')->default('low')->after('subject');
            // Adds an optional explicit time keeper attribute layer
            $table->time('due_time')->nullable()->after('due_date');
        });
    }

    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->dropColumn(['priority', 'due_time']);
        });
    }
};
