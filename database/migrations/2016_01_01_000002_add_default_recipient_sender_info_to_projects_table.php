<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultRecipientSenderInfoToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // fields to save default recipient information on projects
            $table->string('recipient_email')->nullable();
            $table->string('recipient_name')->nullable();
            // fields to save default sender information on projects
            $table->string('sender_email')->nullable();
            $table->string('sender_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('recipient_email');
            $table->dropColumn('recipient_name');
            $table->dropColumn('sender_email');
            $table->dropColumn('sender_name');
        });
    }
}
