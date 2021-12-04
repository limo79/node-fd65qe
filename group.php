<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class group extends Model
{
    use HasFactory;

    public function courses()
    {
        return $this->hasMany(course::class);
    }

    public function questions()
    {
        return $this->hasMany(question::class);
    }
}
