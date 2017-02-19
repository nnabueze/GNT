<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CollectionMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('collection_key');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('amount');
            $table->string('payer_id');
            $table->string('phone');
            $table->string('name');
            $table->string('user_id');
            $table->integer('remittance_id')->unsigned()->index();
            $table->foreign('remittance_id')->references('id')->on('remittances')->onDelete('cascade');
            $table->integer('mda_id')->unsigned()->index();
            $table->foreign('mda_id')->references('id')->on('mdas')->onDelete('cascade');
            $table->integer('revenuehead_id')->unsigned()->index();
            $table->foreign('revenuehead_id')->references('id')->on('revenueheads')->onDelete('cascade');
            $table->integer('subhead_id')->unsigned()->index();
            $table->foreign('subhead_id')->references('id')->on('subheads')->onDelete('cascade');
            $table->string('collection_type');
            $table->string('email');
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
        Schema::dropIfExists('collections');
    }
}
