<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EmployeeNotification extends Model
{
    protected $table = 'employee_notifications';

    protected $fillable = [
        'type', 'title', 'message', 'triggered_by', 'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];



    public function triggeredBy()
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    public function reads()
    {
        return $this->hasMany(NotificationRead::class, 'notification_id');
    }

    public function scopeUnreadFor($query, $userId)
    {
        return $query->whereDoesntHave('reads', function ($q) use ($userId) {
            $q->where('user_id', $userId)->where('is_read', true);
        });
    }


    public static function broadcast(string $type, string $title, string $message, ?int $triggeredBy = null, array $meta = []): self
    {
        return static::create([
            'type'         => $type,
            'title'        => $title,
            'message'      => $message,
            'triggered_by' => $triggeredBy,
            'meta'         => $meta ?: null,
        ]);
    }

    public function iconClass(): string
    {
        return match ($this->type) {
            'new_joinee' => 'bi-person-plus-fill',
            'leave'      => 'bi-calendar-x-fill',
            'birthday'   => 'bi-balloon-heart-fill',
            default      => 'bi-bell-fill',
        };
    }

    public function colorClass(): string
    {
        return match ($this->type) {
            'new_joinee' => 'notif-green',
            'leave'      => 'notif-orange',
            'birthday'   => 'notif-purple',
            default      => 'notif-blue',
        };
    }
}