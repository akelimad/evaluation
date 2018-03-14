<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsObjectifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objectifs', function (Blueprint $table) {
            $table->double('sousTotal')->nullable()->after('ponderation');
            $table->double('total')->nullable()->after('sousTotal');
            $table->double('noteFinal')->nullable()->after('total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('objectifs', function (Blueprint $table) {
            //
        });
    }
}
