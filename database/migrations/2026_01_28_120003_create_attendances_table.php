<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('check_in_status', 1)->default('H');
            $table->string('check_out_status', 1)->default('H');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['teacher_id', 'date']);
            $table->index(['date', 'teacher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
