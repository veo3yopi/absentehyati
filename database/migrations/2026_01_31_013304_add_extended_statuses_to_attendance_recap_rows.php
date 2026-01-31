<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_recap_rows', function (Blueprint $table) {
            $table->unsignedSmallInteger('in_d')->default(0)->after('in_a');
            $table->unsignedSmallInteger('in_w')->default(0)->after('in_d');
            $table->unsignedSmallInteger('in_c')->default(0)->after('in_w');
            $table->unsignedSmallInteger('out_d')->default(0)->after('out_a');
            $table->unsignedSmallInteger('out_w')->default(0)->after('out_d');
            $table->unsignedSmallInteger('out_c')->default(0)->after('out_w');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_recap_rows', function (Blueprint $table) {
            $table->dropColumn(['in_d', 'in_w', 'in_c', 'out_d', 'out_w', 'out_c']);
        });
    }
};
