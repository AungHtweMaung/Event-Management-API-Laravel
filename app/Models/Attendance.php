<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function event(){
        return $this->belongsTo(\App\Models\Event::class);
    }

    public function user(){
        return $this->belongsTo(\App\Models\User::class);
    }

}
