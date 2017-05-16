<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundsweepMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fundsweeps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mda_id')->unsigned()->index();
            $table->foreign('mda_id')->references('id')->on('mdas')->onDelete('cascade');
            $table->integer('history_id')->unsigned()->index();
            $table->foreign('history_id')->references('id')->on('histories')->onDelete('cascade');
            $table->string('account_no');
            $table->string('bank_code');
            $table->string('bank_name');
            $table->string('agency_total');
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
        Schema::drop('fundsweeps');
    }
}
