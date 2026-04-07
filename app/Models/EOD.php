<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EOD extends Model
{
    protected $table = 'eod_reports'; // ← tell Laravel exact table name

    protected $fillable = [
        'user_Id',
        'report_date',
        'tasks_completed',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'tasks_completed' => 'array',  // ← auto decode JSON
        'report_date'     => 'date',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function eods()
    {
        return $this->hasMany(EOD::class,'user_Id');
    }

    // public function eod(){
    //     return $this->hasMany(EOD::class);
    // }
}
