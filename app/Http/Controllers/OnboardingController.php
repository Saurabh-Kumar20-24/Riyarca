<?php

namespace App\Http\Controllers;

use App\Models\OnboardingToken;
use App\Models\EmployeePolicyAcknowledgement;
use App\Models\Policy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OnboardingController extends Controller
{
    public function show($token)
    {
        $record = OnboardingToken::where('token', $token)
                    ->where('is_used', 0)
                    ->where('expires_at', '>', now())
                    ->firstOrFail();

        $employee = User::findOrFail($record->user_id);
        $policies = Policy::where('role_id', $employee->role_id)
                          ->where('is_active', 1)
                          ->get();

        return view('policies.acknowledge', compact('record', 'employee', 'policies', 'token'));
    }

    public function acknowledge(Request $request, $token)
    {
        $request->validate([
            'signature' => 'required|string',
        ]);

        $record = OnboardingToken::where('token', $token)
                    ->where('is_used', 0)
                    ->where('expires_at', '>', now())
                    ->firstOrFail();

        $employee = User::findOrFail($record->user_id);
        $policies = Policy::where('role_id', $employee->role_id)
                          ->where('is_active', 1)
                          ->get();

        foreach ($policies as $policy) {
            EmployeePolicyAcknowledgement::updateOrCreate(
                ['user_id' => $employee->id, 'policy_id' => $policy->id],
                [
                    'signature'       => $request->signature,
                    'ip_address'      => $request->ip(),
                    'acknowledged_at' => now(),
                ]
            );
        }

        $record->update(['is_used' => 1]);

        $employee->update(['onboarding_acknowledged' => 1]);

        return view('policies.success');
    }
}