<?php

use App\Http\Controllers\AttendenceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EodController;
use App\Http\Controllers\HiringController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\RoleController;

Route::get('/', function () {
    return redirect('/login');
});

Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('employee', EmployeeController::class);
    //  NFC 
    Route::get('/nfc/write',        [AttendenceController::class, 'nfcWrite'])->name('nfc.write');
    Route::get('/nfc/scanner',      [AttendenceController::class, 'nfcScanner'])->name('nfc.scanner');
    Route::post('/nfc/scan',        [AttendenceController::class, 'nfcScan'])->name('nfc.scan');
    
    Route::get('/attendance/list', [AttendenceController::class, 'ShowAttendence'])->name('attendence.ShowAttendence');
    Route::resource('attendence', AttendenceController::class);

    Route::get('/eod/list',[EodController::class,'previousEods'])->name('eod.previousEods');
    Route::resource('eod',EodController::class);
    
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

    Route::get('/role/create',[RoleController::class, 'create'])->name('role.create');
    Route::post('/role/store',[RoleController::class, 'store'])->name('role.store');

    Route::get('/auth/profile', [AuthController::class, 'profile'])->name('auth.profile');
    Route::put('/auth/profile/update', [AuthController::class, 'update'])->name('profile.update');

});