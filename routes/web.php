<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return redirect('/login');
});

Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {
   Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('employee', EmployeeController::class);
    Route::get('/auth/profile', [AuthController::class, 'profile'])->name('auth.profile');
    Route::put('/auth/profile/update', [AuthController::class, 'update'])->name('profile.update');
    Route::get('/auth/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('reset_password');
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('reset_password.post');

    Route::get('/auth/EmailVerify', [AuthController::class, 'showEmailVerificationForm'])->name('email_verify');
    // Route::post('/auth/EmailVerify', [AuthController::class, 'sendVerificationEmail'])->name('email_verify.post'); 

    Route::post('/auth/send-otp', [AuthController::class, 'sendOtp'])->name('send.otp');

    Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');

    Route::get('/auth/forgotpassword', [AuthController::class,'showForgotPasswordForm'])->name('forgot_password');

    Route::post('/auth/forgotpassword', [AuthController::class, 'forgotResetPassword'])->name('change_password');
    
});