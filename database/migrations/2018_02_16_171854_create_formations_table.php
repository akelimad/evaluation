<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titre');
            $table->string('perspective');
            $table->date('date');
            $table->tinyInteger('transmit');
            $table->tinyInteger('statut')->nullable();
            $table->integer('entretien_id')->unsigned();
            $table->foreign('entretien_id')->references('id')->on('entretiens')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::drop('formations');
    }
}
