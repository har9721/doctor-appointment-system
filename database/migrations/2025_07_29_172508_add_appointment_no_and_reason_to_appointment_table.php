<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('appointment_no')->nullable()->after('id');
            $table->text('reason')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('appointment', function (Blueprint $table) {
            $table->dropColumn('appointment_no');
            $table->dropColumn('reason');
        });
    }
};
