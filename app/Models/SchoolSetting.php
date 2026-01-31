<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SchoolSetting extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'school_name',
        'address',
        'academic_year',
        'semester',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hero_logo')
            ->useDisk('public')
            ->singleFile();
    }
}
