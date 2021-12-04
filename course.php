<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    use HasFactory;


    public function group()
    {
        return $this->belongsTo(group::class);
    }

    public function teacher()
    {
        return $this->belongsTo(teacher::class);
    }

    public function comments()
    {
        return $this->hasMany(course_comment::class);
    }

    public function certificats()
    {
        return $this->hasMany(course_certificate::class);
    }

    public function exams()
    {
        return $this->hasMany(course_exam::class);
    }

    public function students()
    {
        return $this->hasMany(course_student::class);
    }

    public function titles()
    {
        return $this->hasMany(course_title::class);
    }


    public function polls()
    {
        return $this->hasMany(course_poll::class);
    }

    public function transactions()
    {
        return $this->hasMany(transaction::class);
    }
}
