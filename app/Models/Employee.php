<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Employee extends Model
{
    //
    protected $connection = "mongodb";
    protected $table = "employees";
    protected $fillable = [
        "full_name",
        "gender",
        "phone",
        "birthday",
        "address",
        "email",
        "position",
        "status"
    ];
    protected $casts = [
        // 'days_of_week' => 'array',
        'status' => 'boolean',
    ];
    public function enrollments()
    {
        // This assumes your enrollments table has an 'employee_id' column
        return $this->hasMany(Enrollment::class, 'employee_id', '_id');
    }
    public function schedule()
    {
        // This assumes your enrollments table has an 'employee_id' column
        return $this->hasMany(Schedule::class, 'employee_id', '_id');
    }
}
