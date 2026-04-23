<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadOtp extends Model
{
    protected $table = 'download_otps';

    protected $fillable = [
        'manager_id',
        'otp',
        'expires_at',
        'attempts',
        'is_used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean'
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}