<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Student extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'students';

    protected $fillable = [
        'full_name',
        'gender',
        'phone',
        'email',
        'date_of_birth',
        'address',
        'status',
    ];
}
