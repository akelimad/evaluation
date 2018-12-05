<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntretienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entretiens', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->date('date_limit')->nullable();
            $table->string('titre');
            $table->string('motif')->nullable();
            $table->string('frequence')->nullable();
            $table->string('type');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('conclusion_coll')->nullable();
            $table->string('conclusion_mentor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('entretiens');
    }
}
