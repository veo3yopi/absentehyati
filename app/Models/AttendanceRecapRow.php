<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecapRow extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_recap_id',
        'teacher_id',
        'in_h',
        'in_s',
        'in_i',
        'in_a',
        'in_d',
        'in_w',
        'in_c',
        'out_h',
        'out_s',
        'out_i',
        'out_a',
        'out_d',
        'out_w',
        'out_c',
    ];

    public function recap(): BelongsTo
    {
        return $this->belongsTo(AttendanceRecap::class, 'attendance_recap_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
