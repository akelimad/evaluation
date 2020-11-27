<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsSkillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->integer('hierarchy_function_id')->nullable()->after('function_id');
            $table->integer('functional_function_id')->nullable()->after('hierarchy_function_id');
            $table->integer('formationlevel_id')->nullable()->after('functional_function_id');
            $table->integer('experiencelevel_id')->nullable()->after('formationlevel_id');
            $table->longText('functionnel_relation')->nullable()->after('experiencelevel_id');
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
            $table->dropColumn([
              'hierarchy_function_id',
              'functional_function_id',
              'formationlevel_id',
              'experiencelevel_id',
              'functionnel_relation'
            ]);
        });
    }
}
