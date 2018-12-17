<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubmissionColumnsEntretienUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entretien_user', function (Blueprint $table) {
            $table->tinyInteger('user_submitted')->after('user_id')->default(0);
            $table->tinyInteger('mentor_submitted')->after('user_submitted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entretien_user', function (Blueprint $table) {
            //
        });
    }
}
