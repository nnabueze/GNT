<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InvoiceNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoicenotifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_key');
            $table->string('igr_id');
            $table->string('mda_id');
            $table->string('subhead_id');
            $table->string('name');
            $table->string('phone');
            $table->string('amount');
            $table->string('mda');
            $table->string('subhead');
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
        Schema::drop('invoicenotifications');
    }
}
