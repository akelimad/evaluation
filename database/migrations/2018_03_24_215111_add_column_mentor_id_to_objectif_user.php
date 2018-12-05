<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMentorIdToObjectifUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objectif_user', function (Blueprint $table) {
            $table->integer('mentor_id')->unsigned()->nullable();
            $table->foreign('mentor_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
            $table->dropColumn('mentor_id');
        });
    }
}
