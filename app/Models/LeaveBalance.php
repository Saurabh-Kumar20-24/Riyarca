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

    // ── Relationships ──────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ─────────────────────────────────────────────

    public function scopeCurrentYear($query)
    {
        return $query->where('year', now()->year);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ── Static helpers ─────────────────────────────────────

    /**
     * Get or create a balance row for a user + type + year.
     */
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

    /**
     * Default annual allocation per leave type.
     * Adjust these values to match your company policy.
     */
    public static function defaultAllocation(string $type): float
    {
        return match($type) {
            'casual'    => 12,
            'sick'      => 8,
            'earned'    => 18,
            'optional'  => 2,
            'emergency' => 3,
            'unpaid'    => 0,   // no cap — tracked separately
            'half_day'  => 0,   // counted in 0.5 units from casual/sick
            default     => 0,
        };
    }
}