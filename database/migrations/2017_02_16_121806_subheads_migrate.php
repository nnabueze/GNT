<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubheadsMigrate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subheads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subhead_key');
            $table->string('subhead_code');
            $table->integer('revenuehead_id')->unsigned()->index();
            $table->foreign('revenuehead_id')->references('id')->on('revenueheads')->onDelete('cascade');
            $table->integer('mda_id')->unsigned()->index();
            $table->foreign('mda_id')->references('id')->on('mdas')->onDelete('cascade');
            $table->string('subhead_name');
             $table->enum('taxiable', [0, 1]);
            $table->string('amount');
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
        Schema::dropIfExists('subheads');
    }
}
