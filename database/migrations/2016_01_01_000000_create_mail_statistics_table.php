<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('recipient');
            $table->string('tag');
            $table->string('status');
            $table->integer('project_id')->unsigned();
            $table->string('service_message_id');
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
        Schema::drop('mail_statistics');
    }
}
