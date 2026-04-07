<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobSeeker extends Model
{
    protected $table = 'job_seekers';

    protected $fillable = [
        'job_position_id', 'name', 'email', 'phone',
        'resume_path', 'applied_date', 'status','stage',
        'reviewed_by', 'reviewed_at', 'notes'
    ];

    protected $casts = [
        'applied_date' => 'date',
        'reviewed_at'  => 'datetime',
    ];

    public function position() { return $this->belongsTo(JobPosition::class, 'job_position_id'); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
}
