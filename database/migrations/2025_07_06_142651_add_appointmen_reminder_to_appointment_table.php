<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->timestamp('appointment_reminder_time')->nullable()->after('originalAppointmentDate');
            $table->boolean('isReminderSent')->default(0)->after('appointment_reminder_time');
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('appointment_reminder_time');
            $table->dropColumn('isReminderSent');
        });
    }
};
