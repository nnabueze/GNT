<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrentarlIdMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchantid', function (Blueprint $table) {
            $table->increments('id');
            $table->string("merchantId");
            $table->string("secretKey");
            $table->enum("status",['demo','live'])->default('demo');
            $table->string('response_url')->nullable();
            $table->string('cancel_url')->nullable();
            $table->string('notification_url')->nullable();
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
        Schema::dropIfExists('merchantid');
    }
}
