<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EOD extends Model
{
    protected $table = 'eod_reports'; 

    protected $fillable = [
        'user_id',
        'report_date',
        'tasks_completed',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'tasks_completed' => 'array',  
        'report_date'     => 'date',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function eods()
    {
        return $this->hasMany(EOD::class,'user_Id');
    }

    
}
