<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MdasMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mdas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mda_key');
            $table->enum('mda_category', ["state", "lga","federal"]);
            $table->integer('igr_id')->unsigned()->index();
            $table->foreign('igr_id')->references('id')->on('igrs')->onDelete('cascade');
            $table->string('mda_name');
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
        Schema::dropIfExists('mdas');
    }
}
