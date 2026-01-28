<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceRecap extends Model
{
    use HasFactory;

    public const TYPE_MONTHLY = 'monthly';
    public const TYPE_SEMESTER = 'semester';

    protected $fillable = [
        'type',
        'period_start',
        'period_end',
        'month',
        'academic_year',
        'semester',
        'generated_by',
        'generated_at',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'generated_at' => 'datetime',
    ];

    public function rows(): HasMany
    {
        return $this->hasMany(AttendanceRecapRow::class);
    }

    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
