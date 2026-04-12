<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    protected $fillable = [
        'user_id',
        'leave_type',
        'year',
        'allocated',
        'used',
        'pending',
        'remaining',
    ];

    protected $casts = [
        'allocated' => 'float',
        'used'      => 'float',
        'pending'   => 'float',
        'remaining' => 'float',
    ];

   

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function scopeCurrentYear($query)
    {
        return $query->where('year', now()->year);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }



    public static function getOrCreate(int $userId, string $leaveType, int $year = null): self
    {
        $year = $year ?? now()->year;

        return static::firstOrCreate(
            ['user_id' => $userId, 'leave_type' => $leaveType, 'year' => $year],
            [
                'allocated' => static::defaultAllocation($leaveType),
                'remaining' => static::defaultAllocation($leaveType),
                'used'      => 0,
                'pending'   => 0,
            ]
        );
    }

 
    public static function defaultAllocation(string $type): float
    {
        return match($type) {
            'casual'    => 12,
            'sick'      => 8,
            'earned'    => 18,
            'optional'  => 2,
            'emergency' => 3,
            'unpaid'    => 0,   
            'half_day'  => 0,  
            default     => 0,
        };
    }
}