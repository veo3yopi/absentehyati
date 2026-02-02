<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_recap_rows', function (Blueprint $table) {
            $table->unsignedSmallInteger('late_days')->default(0)->after('in_c');
            $table->unsignedSmallInteger('on_time_days')->default(0)->after('late_days');
            $table->unsignedInteger('late_minutes_total')->default(0)->after('on_time_days');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_recap_rows', function (Blueprint $table) {
            $table->dropColumn(['late_days', 'on_time_days', 'late_minutes_total']);
        });
    }
};
