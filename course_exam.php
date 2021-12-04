<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course_exam extends Model
{
    use HasFactory;

    public function course()
    {
        return $this->belongsTo(course::class);
    }

    public function students()
    {
        return $this->hasMany(course_exam_student::class);
    }

    public function answers()
    {
        return $this->hasMany(course_exam_answer::class);
    }

    public function questions()
    {
        return $this->hasMany(course_exam_question::class);
    }

}
