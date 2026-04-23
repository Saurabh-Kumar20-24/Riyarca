<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    protected $fillable = [
        'employee_id','month','year','payment_mode','bank_name',
        'bank_account','pan_number','pf_number','working_days',
        'days_payable','days_lop','basic','hra','leave_travel_allowance',
        'mobile_broadband_allowance','research_allowance','fuel_allowance',
        'other_allowance','ee_pf_contribution','prof_tax','other_deduction',
        'gross_earnings','total_deductions','net_pay',
        'fixed_annual_salary','variable_annual_salary','ctc_effective_date','created_by',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}