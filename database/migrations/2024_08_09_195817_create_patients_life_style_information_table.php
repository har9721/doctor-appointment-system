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
        Schema::create('patients_life_style_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_ID');
            $table->foreign('patient_ID')->references('id')->on('patients')->onDelete('cascade');
            $table->unsignedBigInteger('smokingStatus_ID');
            $table->foreign('smokingStatus_ID')->references('id')->on('smoking_statuses')->onDelete('cascade');
            $table->unsignedBigInteger('alcoholStatus_ID');
            $table->foreign('alcoholStatus_ID')->references('id')->on('alcohol_statuses')->onDelete('cascade');
            $table->text('exercise');
            $table->boolean('isActive')->default(1);
            $table->boolean('isDeleted')->default(0);
            $table->timestamps();
            $table->unsignedBigInteger('createdBy')->nullable();
            $table->foreign('createdBy')->references('id')->on('patients')->onDelete('cascade');
            $table->unsignedBigInteger('updatedBy')->nullable();
            $table->foreign('updatedBy')->references('id')->on('patients')->onDelete('cascade');
            $table->unsignedBigInteger('deletedBy')->nullable();
            $table->foreign('deletedBy')->references('id')->on('patients')->onDelete('cascade');
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
        Schema::dropIfExists('patients_life_style_information');
    }
};
