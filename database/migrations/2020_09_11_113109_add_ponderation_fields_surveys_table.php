<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPonderationFieldsSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groupes', function (Blueprint $table) {
            $table->double('ponderation')->nullable();
        });
        Schema::table('questions', function (Blueprint $table) {
            $table->double('ponderation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groupes', function (Blueprint $table) {
            $table->dropColumn('ponderation');
        });
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('ponderation');
        });
    }
}
