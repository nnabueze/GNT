<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EbillpaymentMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebillpayments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tin');
            $table->string('name');
            $table->string('phone');
            $table->string('payer_id');
            $table->string('revenuecode');
            $table->string('month');
            $table->string('year');
            $table->string('session_id');
            $table->string('source_bank');
            $table->string('source_account_number');
            $table->string('source_account_name');
            $table->string('destination_bank');
            $table->string('destination_account_name');
            $table->string('destination_account_number');
            $table->string('amount');
            $table->integer('revenuehead_id')->unsigned()->index();
            $table->foreign('revenuehead_id')->references('id')->on('revenueheads')->onDelete('cascade');
            $table->integer('subhead_id')->unsigned()->index();
            $table->foreign('subhead_id')->references('id')->on('subheads')->onDelete('cascade');
            $table->string('refcode');
            $table->enum('tax', [0, 1]);
            $table->dateTime('date');
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
        Schema::drop('ebillpayments');
    }
}
