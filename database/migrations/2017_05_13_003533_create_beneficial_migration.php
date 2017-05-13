<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeneficialMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficials', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('igr_id')->unsigned()->index();
            $table->foreign('igr_id')->references('id')->on('igrs')->onDelete('cascade');
            $table->integer('mda_id')->unsigned()->index();
            $table->foreign('mda_id')->references('id')->on('mdas')->onDelete('cascade');
            $table->string("beneficial_key");
            $table->string("account_no");
            $table->string("bank_code");
            $table->string("bank_name");
            $table->string("notification_no");
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
        Schema::drop('beneficials');
    }
}
