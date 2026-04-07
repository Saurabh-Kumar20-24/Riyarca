<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only admin (role_id 1) or hr (role_id 3) can access
        if (!in_array(Auth::user()->role_id, [1, 9])) {
            abort(403, 'Unauthorized');
        }

        return view('role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only admin (role_id 1) or hr (role_id 3) can store
        if (!in_array(Auth::user()->role_id, [1, 9])) {
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
