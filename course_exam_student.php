<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course_exam_student extends Model
{
    use HasFactory;

    public function exam()
    {
        return $this->belongsTo(course_exam::class);
    }

    public function student()
    {
        return $this->belongsTo(student::class);
    }
}
