<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    //
    protected $fillable = [
        'user_id',
        'attendance_date',
        'check_in',
        'check_out',
        'total_hours',
        'status',
        'created_at',
        'updated_at'
    ];

    public function user(){
        return $this->belongTo(User::class);
    }

    public function attendence(){
        return $this->hasMany(Attendence::class);
    }
}
