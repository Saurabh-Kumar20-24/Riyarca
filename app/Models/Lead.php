<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name', 'company', 'email', 'phone',
        'source', 'status', 'notes',
        'assigned_to', 'created_by'
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function latestActivity()
    {
        return $this->hasOne(LeadActivity::class)->latestOfMany();
    }

    public function activities()
    {
        return $this->hasMany(LeadActivity::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}