<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Adding the new columns after the 'name' column for better database organization
            $table->string('semester')->default('1st Sem')->after('name');
            $table->boolean('is_archived')->default(false)->after('semester');
        });
    }

    public function down()
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Always include the logic to drop the columns in case you need to rollback
            $table->dropColumn(['semester', 'is_archived']);
        });
    }
};
