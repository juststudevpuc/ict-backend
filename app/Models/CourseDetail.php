<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CourseDetail extends Model
{
    //
    protected $connection = "mongodb";
    protected $table = "course_details";
    protected $fillable = [
        'course_id',
        'schedule_id',
        'curriculum'    // Array of topics ✅
    ];

    // 🛠️ Tell Laravel to automatically handle the curriculum as an array
    protected $casts = [
        'curriculum' => 'array',
    ];

    // Relationship: Belongs to ONE Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', '_id');
    }

    // Relationship: Belongs to ONE Schedule
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', '_id');
    }
}
