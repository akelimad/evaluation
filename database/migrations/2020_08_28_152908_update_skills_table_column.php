<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSkillsTableColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn(['axe', 'famille', 'categorie', 'competence', 'entretien_id']);
            $table->string('title');
            $table->longText('description');
            $table->integer('function_id')->unsigned();
            $table->text('savoir');
            $table->text('savoir_faire');
            $table->text('savoir_etre');
            $table->text('mobilite_pro');
            $table->integer('user_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('skills', function (Blueprint $table) {
            //
        });
    }
}
