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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_ID');
            $table->foreign('person_ID')->references('id')->on('person')->onDelete('cascade');
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
};
