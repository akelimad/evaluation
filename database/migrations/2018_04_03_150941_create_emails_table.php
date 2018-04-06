<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sender');
            $table->string('subject');
            $table->longText('message');
            $table->timestamps();
        });

        Schema::create('actions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('action_email', function (Blueprint $table) {
            $table->integer('action_id')->unsigned();
            $table->integer('email_id')->unsigned();
            $table->foreign('email_id')->references('id')->on('emails')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('action_id')->references('id')->on('actions')->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['email_id', 'action_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('emails');
        Schema::drop('actions');
        Schema::drop('action_email');
    }
}
