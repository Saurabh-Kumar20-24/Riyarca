<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveLog extends Model
{
    protected $fillable = [
        'leave_request_id',
        'user_id',
        'action',
        'leave_type',
        'days',
        'note',
    ];

    protected $casts = [
        'days' => 'float',
    ];

    // ── Relationships ──────────────────────────────────────

    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   
}