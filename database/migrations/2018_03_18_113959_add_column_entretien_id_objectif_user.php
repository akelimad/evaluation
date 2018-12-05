<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnEntretienIdObjectifUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objectif_user', function (Blueprint $table) {
            $table->integer('entretien_id')->after('user_id')->unsigned();
            $table->foreign('entretien_id')->references('id')->on('entretiens')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('objectif_user', function (Blueprint $table) {
            $table->dropColumn('entretien_id');
        });
    }
}
