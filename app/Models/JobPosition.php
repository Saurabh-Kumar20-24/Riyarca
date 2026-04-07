<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    protected $fillable = ['title', 'department', 'vacancies', 'status'];

    public function jobSeekers()
    {
        return $this->hasMany(JobSeeker::class, 'job_position_id');
    }
}
