<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentsFieldIntoSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objectif_user', function (Blueprint $table) {
            $table->text('user_comment')->nullable();
            $table->text('mentor_comment')->nullable();
        });
        Schema::table('skill_user', function (Blueprint $table) {
            $table->text('user_comment')->nullable();
            $table->text('mentor_comment')->nullable();
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
            $table->dropColumn(['user_comment', 'mentor_comment']);
        });
        Schema::table('skill_user', function (Blueprint $table) {
            $table->dropColumn(['user_comment', 'mentor_comment']);
        });
    }
}
