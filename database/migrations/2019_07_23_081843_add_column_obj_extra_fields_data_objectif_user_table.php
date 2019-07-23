<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnObjExtraFieldsDataObjectifUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objectif_user', function (Blueprint $table) {
            $table->text('user_extra_fields_data')->after('userAppreciation')->nullable();
            $table->text('mentor_extra_fields_data')->after('mentorAppreciation')->nullable();
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
