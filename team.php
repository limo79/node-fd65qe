<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class team extends Model
{
    use HasFactory;

    public function members()
    {
        return $this->hasMany(team_member::class);
    }

     public function chats()
    {
        return $this->hasMany(team_chat::class)->orderByRaw('date desc,time desc');
    }



}
