<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
   
     * @var list<string>
     */
    protected $fillable = [
        'employee_id',
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'phone',
        'assigned_manager',
        'dob',
        'joining_date',
        'employee_id',
        'profile_image',
        'address',
        'has_accepted_policies',
        'onboarding_acknowledged',
    ];

    /**
   
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
    
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
    public function manager()
    {
        return $this->belongsTo(User::class, 'assigned_manager', 'id');
    }

    public function employees()
    {
        return $this->hasMany(User::class, 'assigned_manager', 'id');
    }
    
    public function attendence()
    {
        return $this->hasOne(Attendence::class, 'user_id')
            ->whereDate('attendance_date', today());
    }

    public function payslips()
    {
        return $this->hasMany(Payslip::class, 'employee_id');
    }
    public function bankDetail()
    {
        return $this->hasOne(EmployeeBankDetail::class);
    }
    public function onboardingToken()
    {
        return $this->hasOne(OnboardingToken::class);
    }

    public function policyAcknowledgements()
    {
        return $this->hasMany(EmployeePolicyAcknowledgement::class);
    }
}
