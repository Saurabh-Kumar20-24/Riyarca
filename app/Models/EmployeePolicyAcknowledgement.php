<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeePolicyAcknowledgement extends Model
{
    protected $fillable = [
        'user_id',
        'policy_id',
        'signature',
        'ip_address',
        'acknowledged_at',
    ];

    protected $casts = [
        'acknowledged_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }
}