<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadLog extends Model
{
    protected $table = 'download_logs';

    public $timestamps = false; 

    protected $fillable = [
        'user_id',
        'file_type',
        'ip_address',
        'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}