<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_recap_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_recap_id')->constrained('attendance_recaps')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('in_h')->default(0);
            $table->unsignedSmallInteger('in_s')->default(0);
            $table->unsignedSmallInteger('in_i')->default(0);
            $table->unsignedSmallInteger('in_a')->default(0);
            $table->unsignedSmallInteger('out_h')->default(0);
            $table->unsignedSmallInteger('out_s')->default(0);
            $table->unsignedSmallInteger('out_i')->default(0);
            $table->unsignedSmallInteger('out_a')->default(0);
            $table->timestamps();

            $table->unique(['attendance_recap_id', 'teacher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_recap_rows');
    }
};
