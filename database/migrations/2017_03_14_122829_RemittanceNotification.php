<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemittanceNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remittancenotifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('remittance_key');
            $table->string('igr_id');
            $table->string('mda_id');
            $table->string('name');
            $table->string('phone');
            $table->string('amount');
            $table->string('mda');
            $table->string('SessionID');
            $table->string('SourceBankCode');
            $table->string('DestinationBankCode');
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
        Schema::drop('remittancenotifications');
    }
}
