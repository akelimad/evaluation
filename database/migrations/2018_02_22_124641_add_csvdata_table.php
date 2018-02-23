<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCsvdataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csvdata', function (Blueprint $table) {
            $table->increments('id');
            $table->string('csv_filename');
            $table->boolean('csv_header')->default(0);
            $table->longText('csv_data');
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
        Schema::drop('csvdata');
    }
}
