<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSurveyIdGroupeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groupes', function (Blueprint $table) {
            $table->integer('survey_id')->unsigned()->after('description');
            $table->foreign('survey_id')->references('id')->on('surveys')
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
        Schema::table('groupes', function (Blueprint $table) {
            $table->dropColumn('survey_id');
        });
    }
}
