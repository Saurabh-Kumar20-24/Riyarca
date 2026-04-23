<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationRead extends Model
{
    protected $table = 'employee_notification_reads';

    protected $fillable = ['notification_id', 'user_id', 'is_read', 'read_at'];

    protected $casts = [
        'read_at' => 'datetime',
    ];
}