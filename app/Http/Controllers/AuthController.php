<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 
use App\Models\Role;

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
        'role'     => 'required|string',
    ]);

    if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
        return back()
            ->withInput($request->only('email', 'role'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    $request->session()->regenerate();

    $user = Auth::user();
    $user->load('role');

    // Check if selected role matches actual role
    if (!$user->role || $user->role->role_name !== $request->role) {
        Auth::logout();
        return back()
            ->withInput($request->only('email', 'role'))
            ->withErrors(['role' => 'Selected role does not match your account.']);
    }

    if ($user->role->role_name === 'admin')   return redirect('dashboard');
    if ($user->role->role_name === 'manager') return redirect('dashboard');
    if ($user->role->role_name === 'user')    return redirect('dashboard');

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
}
