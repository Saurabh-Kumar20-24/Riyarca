<?php
// app/Http/Controllers/EmployeeController.php

namespace App\Http\Controllers;

use App\Mail\DownloadOtpMail;
use App\Models\DownloadLog;
use App\Models\DownloadOtp;
use App\Models\EmployeeBankDetail;
use App\Models\EmployeeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\OnboardingToken;
use App\Mail\OnboardingMail;
use Illuminate\Support\Str;


class EmployeeController extends Controller
{

    public function index(Request $request)
    {
        $authUser = Auth::user();
        $authUser->load('role');

        if ($authUser->role_id == 1 || $authUser->role_id == 9 || $authUser->role_id == 7) {
            $employees = User::with('role', 'manager')
                ->where('id', '!=', 1)
                ->where('is_active', 1);
        } else {
            $employees = User::with('role', 'manager')
                ->where('assigned_manager', $authUser->id)
                ->where('is_active', 1);
        }

        if ($request->search) {
            $employees->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->role) {
            $employees->where('role_id', $request->role);
        }

        $employees = $employees->paginate(10)->withQueryString();


        $roles = Role::all();

        return view('employee.index', compact('employees', 'roles'));
    }

    public function create()
    {
        $authUser = Auth::user();
        $authUser->load('role');

        if ($authUser->role->role_name === 'admin') {
            $roles = Role::whereIn('role_name', ['manager', 'hr'])->get();
        } else {
            $roles = Role::where('role_name', '!=', 'admin')->get();
        }

        $managers = User::where('role_id', 2)->get();

        return view('employee.create', compact('roles', 'managers'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $request->merge([
            'ifsc_code' => strtoupper(trim($request->ifsc_code))
            ]);
            // dd($request);
            
            $request->validate([
                'name'             => 'required|string|max:255',
                'email'            => 'required|email|unique:users,email',
                'password'         => 'required|min:6',
                'role_id'          => 'required|exists:roles,id',
                'phone'            => 'nullable|string|max:20',
                'assigned_manager' => 'nullable|exists:users,id',
                'dob'              => 'nullable|date',
                'joining_date'     => 'nullable|date',
                'bank_name'      => 'nullable|string|max:255',
                'ifsc_code' => 'nullable|string|max:255',
                'account_number' => 'nullable|string|max:30',
                'pan_number'     => 'nullable|string|max:12',
                'pf_number'      => 'nullable|string|max:20',
                ]);
                // dd($request->all());
            
            $year = date('y');
            $prefix = 'RTS-' . $year . '-';
            $last = User::whereNotNull('employee_id')->where('employee_id', 'like', $prefix . '%')->orderBy('id', 'desc')->first();
            
            if ($last && $last->employee_id) {
                $numberPart = substr($last->employee_id, strlen($prefix), 3); //001 nikalana
                $newNumber = (int) $numberPart + 1;
                } else {
                    $newNumber = 1;
                    }
                    
                    $series = str_pad($newNumber, 3, '0', STR_PAD_LEFT); //isse 1 hai to 001 milega
                    $nameLetter = strtoupper(substr($request->name, 0, 1));
                    $employeeId = $prefix . $series . $nameLetter;
                    
                    
                    try {
                        $employee = User::create([
                            'name'             => $request->name,
                            'email'            => $request->email,
                            'password'         => bcrypt($request->password),
                            'role_id'          => $request->role_id,
                            'phone'            => $request->phone,
                            'assigned_manager' => $request->assigned_manager,
                            'dob'              => $request->dob,
                            'joining_date'     => $request->joining_date,
                            'employee_id'      => $employeeId,
                        ]);

                // dd($employee);
               $bankdetails  = EmployeeBankDetail::create([
                    'user_id'        => $employee->id,
                    'bank_name'      => $request->bank_name,
                    'account_number' => $request->account_number,
                    'ifsc_code'      => $request->ifsc_code,
                    'pan_number'     => $request->pan_number,
                    'pf_number'      => $request->pf_number,
                ]);

                // dd($bankdetails);
                    
                    DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        dd($e->getMessage());
                        return back()->with('error', 'Something went wrong!');
                        }
                        
                        // dd($bankdetails);
                        // Generate onboarding token
                        $token = Str::uuid()->toString();

        OnboardingToken::create([
            'user_id'    => $employee->id,   
            'token'      => $token,
            'expires_at' => now()->addDays(7),
            'is_used'    => 0,
        ]);

        $onboardingUrl = route('onboarding.show', ['token' => $token]);
        Mail::to($employee->email)->send(new OnboardingMail($employee, $onboardingUrl));

        EmployeeNotification::broadcast(
            type: 'new_joinee',
            title: 'New Team Member Joined! 🎉',
            message: "{$employee->name} has joined the team. Give them a warm welcome!",
            triggeredBy: $employee->id,
        );

        return redirect()->route('employee.index')
            ->with('success', 'Employee added successfully.');
    }

    public function edit(string $id)
    {
        $employee = User::findOrFail($id);

        $authUser = Auth::user();
        $authUser->load('role');

        if ($authUser->role->role_name === 'admin') {
            $roles = Role::whereIn('role_name', ['manager', 'hr'])->get();
        } else {
            $roles = Role::where('role_name', '!=', 'admin')->get();
        }

        $managers = User::where('role_id', 2)->get();

        return view('employee.edit', compact('employee', 'roles', 'managers'));
    }

    public function update(Request $request, string $id)
    {
        $employee = User::findOrFail($id);

        $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,' . $id,
            'role_id'          => 'required|exists:roles,id',
            'phone'            => 'nullable|string|max:20',
            'assigned_manager' => 'nullable|exists:users,id',
            'is_active'        => 'nullable|boolean',
            'dob' => 'nullable|date',
            'joining_date' => 'nullable|date',
        ]);

        $data = [
            'name'             => $request->name,
            'email'            => $request->email,
            'role_id'          => $request->role_id,
            'phone'            => $request->phone,
            'assigned_manager' => $request->assigned_manager,
            'is_active'        => $request->is_active ?? 1,
            'dob' => $request->dob,
            'joining_date' => $request->joining_date,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $employee->update($data);

        return redirect()->route('employee.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'is_active' => 0
        ]);

        return redirect()->route('employee.index')
            ->with('success', 'Employee deactivated successfully.');
    }


    public function sendOtp(Request $request)
    {
        $user = Auth::user();

        if ($user->role_id !== 2) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        DownloadOtp::where('manager_id', $user->id)
            ->where('is_used', false)
            ->delete();

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DownloadOtp::create([
            'manager_id' => $user->id,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(5),
            'attempts'   => 0,
            'is_used'    => false,
        ]);

        Mail::to($user->email)->send(new DownloadOtpMail($otp, $user->name));

        return response()->json(['success' => true, 'message' => 'OTP sent to your email.']);
    }

    public function verifyAndDownload(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $user = Auth::user();

        if ($user->role_id !== 2) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $record = DownloadOtp::where('manager_id', $user->id)
            ->where('is_used', false)
            ->latest()
            ->first();

        if (!$record) {
            return response()->json(['success' => false, 'message' => 'No OTP found. Please request a new one.']);
        }

        if ($record->attempts >= 3) {
            $record->update(['is_used' => true]);
            return response()->json(['success' => false, 'message' => 'Too many failed attempts. Please request a new OTP.']);
        }

        if (now()->isAfter($record->expires_at)) {
            return response()->json(['success' => false, 'message' => 'OTP has expired. Please request a new one.']);
        }

        if ($record->otp !== $request->otp) {
            $record->increment('attempts');
            $remaining = 3 - $record->fresh()->attempts;
            return response()->json(['success' => false, 'message' => "Invalid OTP. {$remaining} attempt(s) remaining."]);
        }

        $record->update(['is_used' => true]);

        DownloadLog::create([
            'user_id'    => $user->id,
            'file_type'  => 'csv',
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'OTP verified. Starting download...']);
    }

    public function downloadFile()
    {
        $user = Auth::user();

        if ($user->role_id !== 2) abort(403);

        $recentLog = DownloadLog::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMinute())
            ->latest('created_at')
            ->first();

        if (!$recentLog) abort(403, 'Unauthorized download attempt.');

        $employees = User::with(['role', 'manager'])
            ->where('role_id', '!=', 1)
            ->get();

        $filename = 'employees_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($employees) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Employee ID',
                'Name',
                'Email',
                'Phone',
                'DOB',
                'Joining Date',
                'Role',
                'Manager',
                'Status'
            ]);

            foreach ($employees as $emp) {
                fputcsv($handle, [
                    $emp->employee_id,
                    $emp->name,
                    $emp->email,
                    $emp->phone        ?? '-',
                    $emp->dob          ?? '-',
                    $emp->joining_date ?? '-',
                    $emp->role->role_name  ?? '-',
                    $emp->manager->name    ?? '-',
                    $emp->is_active ? 'Active' : 'Inactive',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
