<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationEntretienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation_entretien', function (Blueprint $table) {
            $table->integer('entretien_id')->unsigned();
            $table->integer('evaluation_id')->unsigned();

            $table->foreign('entretien_id')->references('id')->on('entretiens')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('evaluation_id')->references('id')->on('evaluations')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['entretien_id', 'evaluation_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('evaluation_entretien');
    }
}
