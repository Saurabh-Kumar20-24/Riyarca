<?php
// app/Http/Controllers/EmployeeController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;

class EmployeeController extends Controller
{
   
    public function index(Request $request)
    {
        $authUser = Auth::user();
        $authUser->load('role');

        if ($authUser->role_id == 1 || $authUser->role_id == 9) {
            $employees = User::with('role', 'manager')
                             ->where('id', '!=', 1)
                             ->where('is_active', 1);
                          
        } else {
            $employees = User::with('role', 'manager')
                             ->where('assigned_manager', $authUser->id)
                             ->where('is_active', 1);
                            
        }
        // search
            if ($request->search) {
                $employees->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
                });
            }
            // role filter
            if ($request->role) {
                $employees->where('role_id', $request->role);
            }

            $employees = $employees->paginate(10)->withQueryString();

            // roles from DB
            $roles = Role::all();

                return view('employee.index', compact('employees','roles'));
    }

    public function create()
{
    $authUser = Auth::user();
    $authUser->load('role');

    if ($authUser->role->role_name === 'admin') {
        $roles = Role::whereIn('role_name', ['manager','hr'])->get();
    } else {
        $roles = Role::where('role_name', '!=', 'admin')->get();
    }

    $managers = User::where('role_id', 2)->get();

    return view('employee.create', compact('roles', 'managers'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|min:6',
            'role_id'          => 'required|exists:roles,id',
            'phone'            => 'nullable|string|max:20',
            'assigned_manager' => 'nullable|exists:users,id',
            'dob'              => 'nullable|date',
            'joining_date'     => 'nullable|date',
        ]);

        User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'password'         => bcrypt($request->password),
            'role_id'          => $request->role_id,
            'phone'            => $request->phone,
            'assigned_manager' => $request->assigned_manager,
             'dob'              => $request->dob,
            'joining_date'     => $request->joining_date,
        ]);

        return redirect()->route('employee.index')
                         ->with('success', 'Employee added successfully.');
    }

    public function edit(string $id)
{
    $employee = User::findOrFail($id);

    $authUser = Auth::user();
    $authUser->load('role');

    if ($authUser->role->role_name === 'admin') {
        $roles = Role::whereIn('role_name', ['manager','hr'])->get();
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
}