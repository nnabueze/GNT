<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TinMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tin_key');
            $table->string('name');
            $table->string('email');
            $table->text('address');
            $table->integer('igr_id')->unsigned()->index();
            $table->foreign('igr_id')->references('id')->on('igrs')->onDelete('cascade');
            $table->string('tin_no');
            $table->string('temporary_tin');
            $table->string('phone');
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
        Schema::drop('tins');
    }
}
