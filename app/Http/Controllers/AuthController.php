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
        if ($user->role->role_name === 'Office associate')    return redirect('dashboard');

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

        $manager = null;
        if (!in_array($user->role_id, [1, 2]) && $user->assigned_manager) {
            $manager = User::with('role')->find($user->assigned_manager);
        }

        return view('auth.profile', compact('user', 'manager'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'dob'           => ['nullable', 'date'],
            'phone'         => ['nullable', 'string', 'max:10'],
            'address'       => ['nullable', 'string', 'max:200'],
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

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully!');
    }

    public function showEmailVerificationForm()
    {
        return view('auth.EmailVerify');
    }

    // public function sendOtp(Request $request)
    // {

    //     $request->validate([
    //         'email' => 'required|email'
    //     ]);


    //     $user = User::where('email', $request->email)->first();


    //     if (!$user) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => '<div class="error">Email not found</div>'
    //         ]);
    //     }

    //     $otpData = DB::table('otp_verifications')
    //         ->where('user_id', $user->id)
    //         ->first();

    //     if ($otpData && now()->lessThan($otpData->expires_at)) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => '<div class="error">OTP already sent. Please wait 5 minutes.</div>'
    //         ]);
    //     }


    //     $otp = rand(100000, 999999);


    //     DB::table('otp_verifications')->updateOrInsert(
    //         ['user_id' => $user->id],
    //         [
    //             'otp' => $otp,
    //             'expires_at' => Carbon::now()->addMinutes(5),
    //             'created_at' => now(),
    //             'updated_at' => now()
    //         ]
    //     );


    //     Mail::to($request->email)->send(new OtpMail($otp));

    //     return response()->json([
    //         'status' => true,
    //         'message' => '<div class="success">OTP sent to your email</div>'
    //     ]);
    // }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => '<div class="error">Email not found</div>'
            ]);
        }

        $otpData = DB::table('otp_verifications')
            ->where('user_id', $user->id)
            ->first();

        if ($otpData && Carbon::parse($otpData->expires_at)->isFuture()) {
            return response()->json([
                'status'  => false,
                'message' => '<div class="error">OTP already sent. Please wait 5 minutes.</div>'
            ]);
        }

        $otp = rand(100000, 999999);

        DB::table('otp_verifications')->where('user_id', $user->id)->delete();

        DB::table('otp_verifications')->insert([
            'user_id'    => $user->id,
            'otp'        => (string) $otp,
            'expires_at' => Carbon::now()->addMinutes(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Mail::to($request->email)->send(new OtpMail($otp));

        return response()->json([
            'status'  => true,
            'message' => '<div class="success">OTP sent to your email</div>'
        ]);
    }

    // public function verifyOtp(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'otp' => 'required',

    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => '<div class="error">User not found</div>'
    //         ]);
    //     }

    //     $otpData = DB::table('otp_verifications')
    //         ->where('user_id', $user->id)
    //         ->where('otp', $request->otp)
    //         ->first();


    //     if (!$otpData) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => '<div class="error">Invalid OTP</div>'
    //         ]);
    //     }


    //     if (Carbon::now()->gt($otpData->expires_at)) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => '<div class="error">OTP expired</div>'
    //         ]);
    //     }


    //     session(['verified_email' => $request->email]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => '<div class="success">OTP verified successfully</div>'
    //     ]);
    // }

    // public function verifyOtp(Request $request)
    // {
    //     $email = trim($request->email);
    //     $otp   = trim($request->otp);
    //     $type  = $request->type ?? 'forgot_password';

    //     // Find user by email
    //     $user = User::where('email', $email)->first();

    //     if (!$user) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => '<span class="error">Email not found</span>'
    //         ]);
    //     }

    //     // Look in otp_verifications table (same table sendOtp uses)
    //     $record = DB::table('otp_verifications')
    //         ->where('user_id', $user->id)
    //         ->first();

    //     if (!$record || (string)$record->otp != (string)$otp) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => '<span class="error">Invalid OTP</span>'
    //         ]);
    //     }

    //     // Check expiry using expires_at column
    //     if (now()->greaterThan($record->expires_at)) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => '<span class="error">OTP expired</span>'
    //         ]);
    //     }

    //     session(['verified_email' => $email, 'otp_type' => $type]);

    //     // Delete OTP after successful verification
    //     DB::table('otp_verifications')->where('user_id', $user->id)->delete();

    //     $redirect = $type === 'helpdesk'
    //         ? route('helpdesk')
    //         : route('forgot_password');

    //     return response()->json([
    //         'status'   => true,
    //         'message'  => '<span class="success">OTP Verified Successfully!</span>',
    //         'redirect' => $redirect
    //     ]);
    // }

    public function verifyOtp(Request $request)
    {
        try {
            $email = $request->email;
            $otp   = $request->otp;
            $type  = $request->type ?? 'forgot_password';

            $user = User::where('email', $email)->first();
            if (!$user) {
                return response()->json([
                    'status'  => false,
                    'message' => '<span class="error">Email not found</span>'
                ]);
            }

            $record = DB::table('otp_verifications')
                ->where('user_id', $user->id)
                ->first();

            if (!$record || (string)$record->otp !== (string)$otp) {
                return response()->json([
                    'status'  => false,
                    'message' => '<span class="error">Invalid OTP</span>'
                ]);
            }

            if (Carbon::parse($record->expires_at)->isPast()) {
                return response()->json([
                    'status'  => false,
                    'message' => '<span class="error">OTP expired</span>'
                ]);
            }

            session(['verified_email' => $email, 'otp_type' => $type]);

            DB::table('otp_verifications')->where('user_id', $user->id)->delete();

            $redirect = $type === 'helpdesk'
                ? route('helpdesk')
                : route('forgot_password');

            return response()->json([
                'status'   => true,
                'message'  => '<span class="success">OTP Verified Successfully!</span>',
                'redirect' => $redirect
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => '<span class="error">' . $e->getMessage() . '</span>'
            ]);
        }
    }


    public function helpdeskVerified()
    {
        if (!session('verified_email')) {
            return redirect()->route('email_verify')->with('error', 'Please verify your email first.');
        }
        return redirect()->route('helpdesk');
    }


    public function showForgotPasswordForm()
    {
        return view('auth.forgotpassword');
    }

    public function forgotResetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6',
            'confirm_password' => 'required'
        ]);

        if ($request->password != $request->confirm_password) {
            return back()->with('error', 'Password does not match');
        }

        $email = session('verified_email');

        if (!$email) {
            return redirect()->route('email_verify');
        }

        User::where('email', $email)->update([
            'password' => Hash::make($request->password)
        ]);

        $user = User::where('email', $email)->first();
        if ($user) {
            DB::table('otp_verifications')->where('user_id', $user->id)->delete();
        }


        session()->forget('verified_email');

        return redirect()->route('login')->with('success', 'Password Change successfully!');
    }
}
