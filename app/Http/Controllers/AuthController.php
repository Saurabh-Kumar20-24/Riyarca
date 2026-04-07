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
        $user = Auth::user()->load('role');
 
        // Resolve assigned manager name:
        // assigned_manager column stores a user ID whose role_id = 2
        if ($user->assigned_manager) {
            $manager = User::where('id', $user->assigned_manager)
                           ->where('role_id', 2)
                           ->first();
            $user->managerName = $manager ? $manager->name : '—';
        } else {
            $user->managerName = '—';
        }
 
        return view('auth.profile', compact('user'));
    }

     public function update(Request $request)
    {
        $request->validate([
            'dob'           => ['nullable', 'date'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'address'       => ['nullable', 'string', 'max:500'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:3072'],
        ]);
 
        /** @var \App\Models\User $user */
        $user = Auth::user();
 
        // Build update data — preserve existing values if field not submitted
        $data = [
            'dob'     => $request->filled('dob')     ? $request->dob     : $user->dob,
            'phone'   => $request->filled('phone')   ? $request->phone   : $user->phone,
            'address' => $request->filled('address') ? $request->address : $user->address,
        ];
 
        // ── IMAGE UPLOAD ───────────────────────────────────────────────────
        // Saves to:      public/storage/profile_image/filename.ext
        // URL via:       asset('storage/profile_image/filename.ext')
        // DB column:     profile_image  ← filename only (no path)
        // ──────────────────────────────────────────────────────────────────
        if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
 
            $file      = $request->file('profile_image');
            $filename  = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('storage' . DIRECTORY_SEPARATOR . 'profile_image');
 
            // Auto-create folder
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }
 
            // Delete previous image
            if ($user->profile_image) {
                $oldFile = $uploadDir . DIRECTORY_SEPARATOR . $user->profile_image;
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }
 
            // Move file into place
            $file->move($uploadDir, $filename);
 
            // ✅ Write filename into $data so it gets saved to DB
            $data['profile_image'] = $filename;
        }
 
        // ✅ Persist all changes to users table
        $user->fill($data)->save();
 
        return redirect()->route('auth.profile')->with('success', 'Profile updated successfully.');
    }

    //  public function profile()
    // {
    //     $user = Auth::user()->load('role');
 
    //     // Resolve assigned manager name if present
    //     if ($user->assigned_manager) {
    //         $manager = User::find($user->assigned_manager);
    //         $user->managerName = $manager ? $manager->name : '—';
    //     }
 
    //     return view('auth.profile', compact('user'));
    // }

    
    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'dob'           => ['nullable', 'date'],
    //         'phone'         => ['nullable', 'string', 'max:20'],
    //         'address'       => ['nullable', 'string', 'max:500'],
    //         'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    //     ]);
 
    //     /** @var \App\Models\User $user */
    //     $user = Auth::user();
 
    //     $data = [
    //         'dob'     => $request->dob,
    //         'phone'   => $request->phone,
    //         'address' => $request->address,
    //     ];
 
    //     // Handle profile image upload
    //     if ($request->hasFile('profile_image')) {
    //         // Delete old image if exists
    //         if ($user->profile_image) {
    //             Storage::disk('public')->delete('profile_image/' . $user->profile_image);
    //         }
 
    //         $file     = $request->file('profile_image');
    //         $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
 
    //         // Store in storage/app/public/profile_image/
    //         $file->storeAs('profile_image', $filename, 'public');
 
    //         $data['profile_image'] = $filename;
    //     }
 
    //     $user->update($data);
 
    //     return redirect()->route('auth.profile')->with('success', 'Profile updated successfully.');
    // }


    
}
