<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_recaps', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->date('period_start');
            $table->date('period_end');
            $table->unsignedTinyInteger('month')->nullable();
            $table->string('academic_year');
            $table->string('semester');
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->index(['type', 'academic_year', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_recaps');
    }
};
