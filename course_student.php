<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course_student extends Model
{
    use HasFactory;
    public function course()
    {
        return $this->belongsTo(course::class);
    }

    public function student()
    {
        return $this->belongsTo(student::class);
    }

}
