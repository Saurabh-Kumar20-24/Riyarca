<?php

use App\Http\Controllers\AttendenceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EodController;
use App\Http\Controllers\HiringController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return redirect('/login');
});

Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/apply', [HiringController::class, 'applyForm'])->name('hiring.applyForm');
Route::post('/apply', [HiringController::class, 'storeApplication'])->name('hiring.storeApplication');

Route::get('/auth/EmailVerify', [AuthController::class, 'showEmailVerificationForm'])->name('email_verify');
// Route::post('/auth/EmailVerify', [AuthController::class, 'sendVerificationEmail'])->name('email_verify.post'); 
Route::post('/auth/send-otp', [AuthController::class, 'sendOtp'])->name('send.otp');
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
Route::get('/auth/forgotpassword', [AuthController::class, 'showForgotPasswordForm'])->name('forgot_password');
Route::post('/auth/forgotpassword', [AuthController::class, 'forgotResetPassword'])->name('change_password');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('employee', EmployeeController::class);
    //  NFC 
    Route::get('/nfc/write',        [AttendenceController::class, 'nfcWrite'])->name('nfc.write');
    Route::get('/nfc/scanner',      [AttendenceController::class, 'nfcScanner'])->name('nfc.scanner');
    Route::post('/nfc/scan',        [AttendenceController::class, 'nfcScan'])->name('nfc.scan');

    Route::get('/attendance/list', [AttendenceController::class, 'ShowAttendence'])->name('attendence.ShowAttendence');
    Route::resource('attendence', AttendenceController::class);

    Route::get('/eod/list', [EodController::class, 'previousEods'])->name('eod.previousEods');
    Route::resource('eod', EodController::class);

    // Route::patch('leave/{id}/status', [LeaveController::class, 'updateStatus'])->name('leave.updateStatus');
    // Route::get('/leave', [LeaveController::class, 'index'])->name('leave.index');

    // Route::get('/leave', [LeaveController::class, 'index'])->name('leave.index');
    // Route::post('/leave/store', [LeaveController::class, 'store'])->name('leave.store');
    // Route::patch('leave/{id}/status', [LeaveController::class, 'updateStatus'])->name('leave.updateStatus');
    // Route::get('/my-leaves', [LeaveController::class,'myLeaves'])->name('leave.my');


    Route::get('/',                [LeaveController::class, 'index'])->name('index');
    Route::post('/store',          [LeaveController::class, 'store'])->name('store');
    Route::patch('/{id}/status',   [LeaveController::class, 'updateStatus'])->name('updateStatus');
    Route::get('/my',              [LeaveController::class, 'myLeaves'])->name('my');
    Route::patch('/{id}/cancel',   [LeaveController::class, 'cancel'])->name('cancel');

    Route::get('/hiring/accepted', [HiringController::class, 'accepted'])->name('hiring.accepted');
    Route::get('/hiring/rejected', [HiringController::class, 'rejected'])->name('hiring.rejected');
    Route::get('/hiring/newApplication', [HiringController::class, 'newApplication'])->name('hiring.newApplication');
    Route::patch('/hiring/{id}/status', [HiringController::class, 'updateStatus'])->name('hiring.updateStatus');
    Route::patch('/hiring/{id}/stage', [HiringController::class, 'updateStage'])->name('hiring.updateStage');

    Route::get('/role/create', [RoleController::class, 'create'])->name('role.create');
    Route::post('/role/store', [RoleController::class, 'store'])->name('role.store');

    Route::get('/auth/profile', [AuthController::class, 'profile'])->name('auth.profile');
    Route::put('/auth/profile/update', [AuthController::class, 'update'])->name('profile.update');
    Route::get('/auth/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('reset_password');
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('reset_password.post');

    Route::put('/auth/profile/update', [AuthController::class, 'update'])->name('profile.update');

    Route::get('/assign-task', [TaskController::class, 'create'])->name('task.create');
    Route::post('/store-task', [TaskController::class, 'store'])->name('task.store');
    Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('task.my');
    Route::get('/track-tasks', [TaskController::class, 'track'])->name('task.track');
    Route::post('/task-status/{id}', [TaskController::class, 'updateStatus'])->name('task.status');

    Route::get('/leads', [LeadController::class,'index'])->name('leads.index');
    Route::get('/leads/create', [LeadController::class,'create'])->name('leads.create');
    Route::post('/leads/store', [LeadController::class,'store'])->name('leads.store');
    Route::get('/lead-activities/{id}', [LeadController::class,'activities']);
    Route::post('/lead-activity/store', [LeadController::class,'storeActivity'])->name('lead.activity.store');
});
