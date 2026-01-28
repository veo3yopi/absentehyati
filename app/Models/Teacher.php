<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'nuptk',
        'name',
        'gender',
        'phone',
        'status',
    ];

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function recapRows(): HasMany
    {
        return $this->hasMany(AttendanceRecapRow::class);
    }
}
