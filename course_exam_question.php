<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course_exam_question extends Model
{
    use HasFactory;

    public function exam()
    {
        return $this->belongsTo(course_exam::class);
    }

    public function question()
    {
        return $this->belongsTo(question::class);
    }
}
