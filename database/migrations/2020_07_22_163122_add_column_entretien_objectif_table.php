<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnEntretienObjectifTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entretien_objectifs', function (Blueprint $table) {
            $table->string('type')->after('id')->nullable();
            $table->string('team')->after('type')->nullable();
            $table->date('deadline')->after('description')->nullable();
            $table->text('indicators')->after('deadline')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entretien_objectifs', function (Blueprint $table) {
            //
        });
    }
}
