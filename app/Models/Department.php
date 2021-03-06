<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function coordinators()
    {
        return $this->hasMany(Coordinator::class);
    }

    public function courseStudent()
    {
        return $this->hasManyThrough(Student::class, Course::class);
    }
}
