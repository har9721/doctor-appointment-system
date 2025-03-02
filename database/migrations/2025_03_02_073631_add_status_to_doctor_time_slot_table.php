<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('doctor_time_slots', function (Blueprint $table) {
            $table->enum('status',['available','not available'])->default('available')->after('isBooked');
        });
    }

    public function down()
    {
        Schema::table('doctor_time_slots', function (Blueprint $table) {
            $table->dropColumn(['status']);
        });
    }
};
