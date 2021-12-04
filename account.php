<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class account extends Model
{
    use HasFactory;

    protected $fillable=['account_id','name','username','password','last','level','block','mobile'];


    public function student()
    {
        return $this->belongsTo(student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(teacher::class);
    }

    public function tickets()
    {
        return $this->hasMany(ticket::class);
    }

    public function logs()
    {
        return $this->hasMany(log::class);
    }

    public function transactions()
    {
        return $this->hasMany(transaction::class);
    }
}
