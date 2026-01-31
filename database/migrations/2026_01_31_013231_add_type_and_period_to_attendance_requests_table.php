<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_requests', function (Blueprint $table) {
            $table->string('type', 20)->nullable()->after('teacher_id');
            $table->date('start_date')->nullable()->after('type');
            $table->date('end_date')->nullable()->after('start_date');
            $table->index(['teacher_id', 'start_date', 'end_date']);
            $table->index(['type', 'status']);
        });

        DB::table('attendance_requests')
            ->whereNull('start_date')
            ->update([
                'type' => DB::raw("check_in_status"),
                'start_date' => DB::raw("date"),
                'end_date' => DB::raw("date"),
            ]);

        Schema::table('attendance_requests', function (Blueprint $table) {
            $table->dropUnique('attendance_requests_teacher_date_status_unique');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_requests', function (Blueprint $table) {
            $table->unique(['teacher_id', 'date', 'status'], 'attendance_requests_teacher_date_status_unique');
            $table->dropIndex(['teacher_id', 'start_date', 'end_date']);
            $table->dropIndex(['type', 'status']);
            $table->dropColumn(['type', 'start_date', 'end_date']);
        });
    }
};
