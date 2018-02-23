<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemunerationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remunerations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->double('amount',8, 2);
            $table->string('reason');
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
        Schema::drop('remunerations');
    }
}
