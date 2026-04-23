<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBankDetail extends Model
{
    protected $table = 'employee_bank_details';

    protected $fillable = [
        'user_id',
        'bank_name',
        'account_number',
        'ifsc_code',
        'branch',
        'pan_number',
        'pf_number', 
    ];

   
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}