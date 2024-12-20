<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function user() {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function attendances() {
        return $this->hasMany(\App\Models\Attendance::class);
    }


}
