<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UploadsweepMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploadsweeps', function (Blueprint $table) {
            $table->increments('id');
            $table->string("agency");
            $table->integer('mda_id')->unsigned()->index();
            $table->foreign('mda_id')->references('id')->on('mdas')->onDelete('cascade');
            $table->decimal('collected_amount', 18, 2);
            $table->decimal('agency_amount', 18, 2);
            $table->decimal('remitted_amount', 18, 2);
            $table->dateTime('remitted_date');
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
        Schema::drop('uploadsweeps');
    }
}
