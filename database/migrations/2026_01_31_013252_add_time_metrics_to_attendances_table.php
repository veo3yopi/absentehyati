<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedSmallInteger('late_minutes')->default(0)->after('check_in_time');
            $table->unsignedSmallInteger('early_leave_minutes')->default(0)->after('check_out_time');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['late_minutes', 'early_leave_minutes']);
        });
    }
};
