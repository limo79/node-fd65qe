<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course_title_content extends Model
{
    use HasFactory;

    public function title()
    {
        return $this->belongsTo(course_title::class);
    }
}
