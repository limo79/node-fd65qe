<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class student extends Model
{
    use HasFactory;

    protected $fillable=['id','name','register','national','birthday','mobile','email','address','father','pic'];


    public function account()
    {
        return $this->belongsTo(account::class);
    }

    public function courses()
    {
        return $this->hasMany(course_student::class);
    }

    public function tickets()
    {
        return $this->hasMany(course_student::class);
    }

    public function teams()
    {
        return $this->hasMany(team_member::class);
    }

    public function exams()
    {
        return $this->hasMany(course_exam_student::class);
    }
}
