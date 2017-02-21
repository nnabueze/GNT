<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RevenueheadsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revenueheads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('revenueheads_key');
            $table->string('revenue_code');
            $table->integer('mda_id')->unsigned()->index();
            $table->foreign('mda_id')->references('id')->on('mdas')->onDelete('cascade');
            $table->string('revenue_name');
            $table->string('amount');
            $table->enum('sub_heads', [0, 1]);
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
        Schema::dropIfExists('revenueheads');
    }
}
