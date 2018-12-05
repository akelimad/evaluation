<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skill_users', function (Blueprint $table) {
            $table->integer('skill_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('mentor_id')->unsigned();
            $table->integer('entretien_id')->unsigned();
            $table->integer('objectif');
            $table->integer('auto');
            $table->integer('nplus1');
            $table->integer('ecart');
            $table->foreign('skill_id')->references('id')->on('skills')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('mentor_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('entretien_id')->references('id')->on('entretiens')->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['skill_id', 'user_id', 'entretien_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('skill_users');
    }
}
