<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $table = 'leave_requests';

    protected $fillable = [
        'user_id',
        'leave_type',
        'from_date',
        'to_date',
        'total_days',
        'duration',
        'half_day_type',
        'reason',
        'handover',
        'contact_email',
        'contact_phone',
        'document',
        'status',
        'is_unpaid',
        'is_emergency',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'from_date'   => 'date',
        'to_date'     => 'date',
        'reviewed_at' => 'datetime',
        'total_days'  => 'float',
    ];

    // ── Relationships ──────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function logs()
    {
        return $this->hasMany(LeaveLog::class);
    }
}