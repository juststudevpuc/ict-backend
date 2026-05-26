<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; // Add this!
use MongoDB\Laravel\Eloquent\Model;

class Course extends Model // 🛠️ Capitalized 'Course'
{
    protected $connection = "mongodb";
    protected $table = "courses";

    protected $fillable = [
        "title",
        "description",
        "duration_hours",
        "status",
        "price",
        "image",
        "image_url",
        "image_public_id",
    ];
    protected $casts = [
        'price' => 'integer',
        'status' => 'boolean',
    ];

    public function schedules(): HasMany
    {
        // 🛠️ explicitly tell MongoDB how to link the tables!
        return $this->hasMany(Schedule::class, 'course_id', '_id');
    }

    public function courseDetail(): HasOne
    {
        // 🛠️ explicitly tell MongoDB how to link the tables!
        return $this->hasOne(CourseDetail::class, 'course_id', '_id');
    }
}
