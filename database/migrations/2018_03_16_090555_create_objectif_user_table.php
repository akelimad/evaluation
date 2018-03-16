<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectifUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objectif_user', function (Blueprint $table) {
            $table->integer('objectif_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('objectif_id')->references('id')->on('objectifs')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('note');
            $table->string('appreciation');
            $table->tinyInteger('objNplus1')->default(0);
            $table->primary(['objectif_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('objectif_user', function (Blueprint $table) {
            Schema::drop('objectif_user');
        });
    }
}
