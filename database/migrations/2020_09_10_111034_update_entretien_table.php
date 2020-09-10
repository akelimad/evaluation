<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEntretienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entretiens', function (Blueprint $table) {
            $table->dropColumn('model');
            $table->integer('model_id')->after('titre')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entretiens', function (Blueprint $table) {
            //
        });
    }
}
