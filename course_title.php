<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course_title extends Model
{
    use HasFactory;

    public function course()
    {
        return $this->belongsTo(course::class);
    }

    public function contents()
    {
        return $this->hasMany(course_title_content::class);
    }
}
