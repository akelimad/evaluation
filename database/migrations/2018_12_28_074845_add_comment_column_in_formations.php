<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentColumnInFormations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formations', function (Blueprint $table) {
            $table->text('coll_comment')->after('user_id')->nullable();
            $table->text('mentor_comment')->after('coll_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formations', function (Blueprint $table) {
            //
        });
    }
}
