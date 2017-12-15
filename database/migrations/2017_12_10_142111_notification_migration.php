<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NotificationMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('SessionID')->unique();
            $table->string('PayerPhoneNumber')->nullable();
            $table->string('PayerName')->nullable();
            $table->string('ReferenceCode')->nullable();
            $table->decimal('amount',20,2);
            //$table->string('TransactionDate')->nullable();
            $table->dateTime('TransactionDate')->nullable();
            $table->enum('paymentType',['centralpay','ussd','mcash']);
            $table->enum('status',['failed','success']);
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
        Schema::dropIfExists('notifications');
    }
}
