<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Schedule extends Model
{
    protected $connection = 'mongodb';
    protected $table = "schedules";

    protected $fillable = [
        "course_id",
        "instructor_id", // Added
        "group_name",    // Added
        "room",          // Added
        "shift",
        "start_date",
        "end_date",      // Added
        "days_of_week",
        "start_time",
        "end_time",
        "status",
    ];

    // --- ADD THIS TO MAGICALLY HANDLE MONGODB ARRAYS ---
    protected $casts = [
        // 'days_of_week' => 'array',
        'status' => 'boolean',
    ];

    public function course() {
        return $this->belongsTo(Course::class, "course_id", "_id");
    }

    // --- ADD THE INSTRUCTOR RELATIONSHIP ---
    public function instructor() {
        return $this->belongsTo(Instructor::class, "instructor_id", "_id");
    }
}
