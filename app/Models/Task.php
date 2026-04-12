<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'assigned_by',
        'assigned_to',
        'priority',
        'status',
        'due_date'
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class,'assigned_to');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class,'assigned_by');
    }
}