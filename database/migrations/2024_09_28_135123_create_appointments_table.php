<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctorTimeSlot_ID')->nullable();
            $table->foreign('doctorTimeSlot_ID')->references('id')->on('doctor_time_slots')->onDelete('cascade');
            $table->unsignedBigInteger('patient_ID')->nullable();
            $table->foreign('patient_ID')->references('id')->on('patients')->onDelete('cascade');
            $table->date('appointmentDate')->nullable();
            $table->enum('status',['pending','confirmed','cancelled'])->default('pending');
            $table->boolean('isBooked')->default('1');
            $table->boolean('isCancel')->default('0');
            $table->boolean('isActive')->default('1');
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
