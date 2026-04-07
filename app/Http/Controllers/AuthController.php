<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    //
     public function index()
    {
        return view('auth.login');
    }

   // app/Http/Controllers/AuthController.php

    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('auth.login');
        }

        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        $request->session()->regenerate();

        $user = Auth::user();
        // $user->load('role');

        // Check if selected role matches actual role
        // if (!$user->role || $user->role->role_name !== $request->role) {
        //     Auth::logout();
        //     return back()
        //         ->withInput($request->only('email', 'role'))
        //         ->withErrors(['role' => 'Selected role does not match your account.']);
        // }

        if ($user->role->role_name === 'admin')   return redirect('dashboard');
        if ($user->role->role_name === 'manager') return redirect('dashboard');
        if ($user->role->role_name === 'user')    return redirect('dashboard');
        if ($user->role->role_name === 'HR')    return redirect('dashboard');

        Auth::logout();
        return back()->withErrors(['email' => 'Unauthorized role.']);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function profile()
    {
        // Eager-load the role relationship so $user->role->name works in the view
        $user = Auth::user()->load('role');
        return view('auth.profile', compact('user'));
    }

    
    public function update(Request $request)
    {
        $user = Auth::user();
 
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'phone'         => 'nullable|string|max:20',
            'password'      => 'nullable|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
 
        // ── Handle profile photo upload ──
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo = $path;
        }
 
        // ── Update fields ──
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
 
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
 
        $user->save();
 
        return redirect()->route('auth.profile')->with('success', 'Profile updated successfully.');
    }

}
