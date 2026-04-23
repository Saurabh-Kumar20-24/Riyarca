<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    
    public function index()
    {
        //
    }

   
    public function create()
    {
        if (!in_array(Auth::user()->role_id, [1,2, 9])) {
            abort(403, 'Unauthorized');
        }

        return view('role.create');
    }

    
    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1,2, 9])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'role_name' => 'required|string|max:100|unique:roles,role_name',
        ]);

        Role::create([
            'role_name'  => strtolower(trim($request->role_name)),
            'created_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Role added successfully.');
    }

   
    public function show(string $id)
    {
        //
    }

    
    public function edit(string $id)
    {
        //
    }

   
    public function update(Request $request, string $id)
    {
        //
    }

  
    public function destroy(string $id)
    {
        //
    }
}
