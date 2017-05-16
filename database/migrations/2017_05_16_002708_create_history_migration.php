<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('igr_id')->unsigned()->index();
            $table->foreign('igr_id')->references('id')->on('igrs')->onDelete('cascade');
            $table->string('history_key');
            $table->string('history_name');
            $table->dateTime('startdate');
            $table->dateTime('enddate');
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
        Schema::drop('histories');
    }
}
