<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            // Adds the user_id column right after the 'id' column and links it to the users table
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            // Drops the foreign key and the column if we ever need to rollback
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
