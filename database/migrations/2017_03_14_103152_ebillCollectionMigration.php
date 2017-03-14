<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EbillCollectionMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebillcollections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('collection_key');
            $table->string('Tin');
            $table->string('collection_type');
            $table->string('igr_id');
            $table->string('mda_id');
            $table->string('subhead_id');
            $table->string('name');
            $table->string('phone');
            $table->string('mda');
            $table->string('subhead');
            $table->string('amount');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('SessionID');
            $table->string('SourceBankCode');
            $table->string('DestinationBankCode');
            $table->enum("tax",['0','1']);
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
        Schema::drop('ebillcollections');
    }
}
