<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateObjectifUserTableColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objectif_user', function (Blueprint $table) {
            $table->dropColumn(['userNote', 'realise', 'ecart', 'userAppreciation', 'user_extra_fields_data', 'objNplus1', 'mentorNote', 'mentorAppreciation', 'mentor_extra_fields_data']);
            $table->text('indicators_data');
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
        Schema::table('objectif_user', function (Blueprint $table) {
            //
        });
    }
}
