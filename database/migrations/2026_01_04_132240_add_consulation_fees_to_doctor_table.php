<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->decimal('consultationFees', 8, 2)->after('experience')->default(0);
            $table->decimal('followUpFees', 8, 2)->after('consultationFees')->default(0);
            $table->string('payment_mode')->after('followUpFees')->nullable()->comment('none, advance, full');
            $table->decimal('advanceFees', 8, 2)->after('payment_mode')->default(0);
        });
    }

    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn(['consultationFees', 'followUpFees', 'payment_mode', 'advanceFees']);
        });
    }
};
