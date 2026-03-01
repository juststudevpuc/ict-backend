<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Enrollment extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'enrollments';

    protected $fillable = [
        'student_id',
        'course_id',
        'schedule_id',
        'enrollment_date',
        'total_price',
        'discount',
        'paid_amount',
        'payment_status',
        'status',
    ];
    protected $casts = [
        'total_price' => 'double',
        'paid_amount' => 'double',
        'discount'    => 'double',
        'status'      => 'boolean',
    ];

    // --- Relationships ---

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', '_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', '_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', '_id');
    }
}
