<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    //
    protected $fillable = [
        'user_id',
        'employee_id', 
        'attendance_date',
        'check_in',
        'check_out',
        'total_hours',
        'status',
        'late_status',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendence()
    {
        return $this->hasOne(Attendence::class, 'user_id')
                    ->whereDate('attendance_date', today());
    }
}
