<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class teacher extends Model
{
    use HasFactory;

    protected $fillable=['id','name','register','national','birthday','mobile','email','address','cv','pic'];

    public function account()
    {
        return $this->belongsTo(account::class);
    }

    public function courses()
    {
        return $this->hasMany(course::class);
    }

}
