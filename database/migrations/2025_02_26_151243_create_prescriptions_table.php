<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_ID');
            $table->foreign('appointment_ID')->references('id')->on('appointments')->onDelete('cascade');
            $table->unsignedBigInteger('doctor_ID'); // the reason behind is to fetch data faster. I can fetch doctor details using the appointment->doctorTimeSlot_Id then doctor but it will slow down the execution that's why I have add doctor id here.
            $table->foreign('doctor_ID')->references('id')->on('doctors')->onDelete('cascade');
            $table->unsignedBigInteger('patient_ID');
            $table->foreign('patient_ID')->references('id')->on('patients')->onDelete('cascade');
            $table->text('medicines');
            $table->text('instructions')->nullable();
            $table->boolean('isActive')->default(1);
            $table->boolean('isDeleted')->default(0);
            $table->timestamps();
            $table->unsignedBigInteger('createdBy')->nullable();
            $table->foreign('createdBy')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updatedBy')->nullable();
            $table->foreign('updatedBy')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('deletedBy')->nullable();
            $table->foreign('deletedBy')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('deletedAt')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prescriptions');
    }
};
