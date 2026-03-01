<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Instructor extends Model
{
    //
    protected $connection = "mongodb";
    protected $table = "instructors";
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'specialization',
        // 'profile_picture',
        // 'course_ids',
        'status'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'instructor_id', '_id');
    }
}
