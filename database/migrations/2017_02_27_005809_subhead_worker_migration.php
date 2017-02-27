<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubheadWorkerMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subhead_worker', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subhead_id')->unsigned()->index();
            $table->foreign('subhead_id')->references('id')->on('subheads')->onDelete('cascade');
            $table->integer('worker_id')->unsigned()->index();
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('cascade');
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
        Schema::drop('subhead_worker');
    }
}
