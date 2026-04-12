<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    
    public function index()
    {
        return view('auth.login');
    }



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
       
        if ($user->role->role_name === 'admin')   return redirect('dashboard');
        if ($user->role->role_name === 'manager') return redirect('dashboard');
        if ($user->role->role_name === 'user')    return redirect('dashboard');
        if ($user->role->role_name === 'HR')    return redirect('dashboard');
        if ($user->role->role_name === 'developer')    return redirect('dashboard');
        if ($user->role->role_name === 'student')    return redirect('dashboard');
        if ($user->role->role_name === 'BDA')    return redirect('dashboard');

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


    public function helpdesk()
{
    $data = [
        'phone' => '+91 9876543210',
        'email' => 'support@gmail.com',
        'website' => 'www.yoursite.com',
        'address' => 'Varanasi, India',
        'timing_days' => 'Mon - Fri',
        'timing_hours' => '9:00 AM - 6:00 PM'
    ];

    return view('auth.helpdesk', compact('data'));
}
    public function profile()
    {
        $user = Auth::user()->load('role');

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

        $user = Auth::user();

        $data = [
            'dob'     => $request->filled('dob')     ? $request->dob     : $user->dob,
            'phone'   => $request->filled('phone')   ? $request->phone   : $user->phone,
            'address' => $request->filled('address') ? $request->address : $user->address,
        ];

        if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {

            $file      = $request->file('profile_image');
            $filename  = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('storage' . DIRECTORY_SEPARATOR . 'profile_image');

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            if ($user->profile_image) {
                $oldFile = $uploadDir . DIRECTORY_SEPARATOR . $user->profile_image;
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }

            $file->move($uploadDir, $filename);

            $data['profile_image'] = $filename;
        }

        $user->fill($data)->save();

        return redirect()->route('auth.profile')->with('success', 'Profile updated successfully.');
    }
    public function showResetPasswordForm()
    {
        return view('auth.reset_password');
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect'
            ]);
        }


        // Update password
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully!');
    }

    public function showEmailVerificationForm()
    {
        return view('auth.EmailVerify');
    }

    public function sendOtp(Request $request)
    {
   
        $request->validate([
            'email' => 'required|email'
        ]);

     
        $user = User::where('email', $request->email)->first();
      

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => '<div class="error">Email not found</div>'
        ]);
    }

    // ✅ OTP Restriction (ADDED)
    $otpData = DB::table('otp_verifications')
                ->where('user_id', $user->id)
                ->first();

    if ($otpData && now()->lessThan($otpData->expires_at)) {
        return response()->json([
            'status' => false,
            'message' => '<div class="error">OTP already sent. Please wait 5 minutes.</div>'
        ]);
    }

        
        $otp = rand(100000, 999999);

        
        DB::table('otp_verifications')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(5),
                'created_at' => now(),
                'updated_at' => now()
            ]
        );


        Mail::to($request->email)->send(new OtpMail($otp));
       
        return response()->json([
            'status' => true,
            'message' => '<div class="success">OTP sent to your email</div>'
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => '<div class="error">User not found</div>'
            ]);
        }

        $otpData = DB::table('otp_verifications')
            ->where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->first();

       
        if (!$otpData) {
            return response()->json([
                'status' => false,
                'message' => '<div class="error">Invalid OTP</div>'
            ]);
        }

     
        if (Carbon::now()->gt($otpData->expires_at)) {
            return response()->json([
                'status' => false,
                'message' => '<div class="error">OTP expired</div>'
            ]);
        }

      
        session(['verified_email' => $request->email]);

        return response()->json([
            'status' => true,
            'message' => '<div class="success">OTP verified successfully</div>'
        ]);
    }





    public function showForgotPasswordForm()
    {
        return view('auth.forgotpassword');
    }

    public function forgotResetPassword(Request $request)
    {
        // Validate
        $request->validate([
            'password' => 'required|min:6',
            'confirm_password' => 'required'
        ]);

        // Match password
        if ($request->password != $request->confirm_password) {
            return back()->with('error', 'Password does not match');
        }

        // Get verified email from session
        $email = session('verified_email');

        if (!$email) {
            return redirect()->route('email_verify');
        }

        User::where('email', $email)->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete OTP
        $user = User::where('email', $email)->first();
        if ($user) {
            DB::table('otp_verifications')->where('user_id', $user->id)->delete();
        }


        session()->forget('verified_email');

        // Redirect to login
        return redirect()->route('login')->with('success', 'Password Change successfully!');
    }
}
