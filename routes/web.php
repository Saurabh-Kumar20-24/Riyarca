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

});