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
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('instructor_name')->nullable()->after('code');
            $table->string('instructor_email')->nullable()->after('instructor_name');
            $table->string('consultation_hours')->nullable()->after('instructor_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['instructor_name', 'instructor_email', 'consultation_hours']);
        });
    }
};
