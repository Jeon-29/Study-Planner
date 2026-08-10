<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColorFromTodosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('todos', function (Blueprint $table) {
            // This safely removes the standalone color column
            $table->dropColumn('color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('todos', function (Blueprint $table) {
            // Put it back if we ever roll back this migration
            $table->string('color', 7)->default('#78716C');
        });
    }
}
