<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class team_chat extends Model
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(team::class);
    }

    public function account()
    {
        return $this->belongsTo(account::class);
    }


}
