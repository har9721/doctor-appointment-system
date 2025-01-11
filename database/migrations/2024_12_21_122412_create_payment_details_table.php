<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_ID');
            $table->foreign('appointment_ID')->references('id')->on('appointments')->onDelete('cascade');
            $table->string('res_payment_id')->nullable();
            $table->string('order_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('method')->nullable();
            $table->string('currency')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('amount')->nullable();
            $table->string('status')->nullable();
            $table->longText('json_response')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('createdBy')->nullable();
            $table->foreign('createdBy')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_details');
    }
};
