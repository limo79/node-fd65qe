<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class team_member extends Model
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(team::class);
    }

    public function student()
    {
        return $this->belongsTo(student::class);
    }
}
