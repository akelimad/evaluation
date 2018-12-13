<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entretiens', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->after('id')->nullable();
        });
        Schema::table('emails', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->after('id')->nullable();
        });
        Schema::table('permission_role', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->after('role_id')->nullable();
        });
        Schema::table('surveys', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entretienobjectifs', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
