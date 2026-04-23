<?php

use App\Http\Controllers\AttendenceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EodController;
use App\Http\Controllers\HiringController;
use App\Http\Controllers\JobPositionController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return redirect('/login');
});

Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/auth/helpdesk', [AuthController::class, 'helpdesk'])->name('helpdesk');
// Route::get('/auth/helpdesk-verified', [AuthController::class, 'helpdeskVerified'])->name('helpdesk.verified');

Route::get('/apply', [HiringController::class, 'applyForm'])->name('hiring.applyForm');
Route::post('/apply', [HiringController::class, 'storeApplication'])->name('hiring.storeApplication');

Route::get('/auth/EmailVerify', [AuthController::class, 'showEmailVerificationForm'])->name('email_verify');
Route::post('/auth/send-otp', [AuthController::class, 'sendOtp'])->name('send.otp');
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
Route::get('/auth/forgotpassword', [AuthController::class, 'showForgotPasswordForm'])->name('forgot_password');
Route::post('/auth/forgotpassword', [AuthController::class, 'forgotResetPassword'])->name('change_password');


Route::get('/onboarding/{token}',  [OnboardingController::class, 'show'])->name('onboarding.show');
Route::post('/onboarding/{token}', [OnboardingController::class, 'acknowledge'])->name('onboarding.acknowledge');


Route::middleware(['auth'])->group(function () {
    Route::get('/policies/accept',  [PolicyController::class, 'acceptPage'])->name('policies.accept');
    Route::post('/policies/accept', [PolicyController::class, 'acceptAll'])->name('policies.acceptAll');
});


Route::middleware(['auth', 'policy.accepted'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('employee', EmployeeController::class);


    Route::get('/nfc/write',   [AttendenceController::class, 'nfcWrite'])->name('nfc.write');
    Route::get('/nfc/scanner', [AttendenceController::class, 'nfcScanner'])->name('nfc.scanner');
    Route::post('/nfc/scan',   [AttendenceController::class, 'nfcScan'])->name('nfc.scan');

    Route::get('/attendance/list', [AttendenceController::class, 'ShowAttendence'])->name('attendence.ShowAttendence');
    Route::resource('attendence', AttendenceController::class);

    Route::get('/eod/list', [EodController::class, 'previousEods'])->name('eod.previousEods');
    Route::resource('eod', EodController::class);

    Route::prefix('leave')->group(function () {
        Route::get('/',              [LeaveController::class, 'index'])->name('index');
        Route::post('/store',        [LeaveController::class, 'store'])->name('store');
        Route::patch('/{id}/status', [LeaveController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/my',            [LeaveController::class, 'myLeaves'])->name('my');
        Route::patch('/{id}/cancel', [LeaveController::class, 'cancel'])->name('cancel');
        Route::get('/export/csv',    [LeaveController::class, 'exportCsv'])->name('leave.exportCsv');
        Route::get('/export/pdf',    [LeaveController::class, 'exportPdf'])->name('leave.exportPdf');
    });

    Route::get('/hiring/accepted',           [HiringController::class, 'accepted'])->name('hiring.accepted');
    Route::get('/hiring/rejected',           [HiringController::class, 'rejected'])->name('hiring.rejected');
    Route::get('/hiring/newApplication',     [HiringController::class, 'newApplication'])->name('hiring.newApplication');
    Route::patch('/hiring/{id}/status',      [HiringController::class, 'updateStatus'])->name('hiring.updateStatus');
    Route::patch('/hiring/{id}/stage',       [HiringController::class, 'updateStage'])->name('hiring.updateStage');

    Route::get('/role/create',  [RoleController::class, 'create'])->name('role.create');
    Route::post('/role/store',  [RoleController::class, 'store'])->name('role.store');

    Route::get('/auth/profile',           [AuthController::class, 'profile'])->name('auth.profile');
    Route::put('/auth/profile/update',    [AuthController::class, 'update'])->name('profile.update');
    Route::get('/auth/reset-password',    [AuthController::class, 'showResetPasswordForm'])->name('reset_password');
    Route::post('/auth/reset-password',   [AuthController::class, 'resetPassword'])->name('reset_password.post');

    Route::get('/assign-task',         [TaskController::class, 'create'])->name('task.create');
    Route::post('/store-task',         [TaskController::class, 'store'])->name('task.store');
    Route::get('/my-tasks',            [TaskController::class, 'myTasks'])->name('task.my');
    Route::get('/track-tasks',         [TaskController::class, 'track'])->name('task.track');
    Route::post('/task-status/{id}',   [TaskController::class, 'updateStatus'])->name('task.status');

    Route::get('/leads',               [LeadController::class, 'index'])->name('leads.index');
    Route::get('/leads/create',        [LeadController::class, 'create'])->name('leads.create');
    Route::post('/leads/store',        [LeadController::class, 'store'])->name('leads.store');
    Route::get('/lead-activities/{id}', [LeadController::class, 'activities']);
    Route::post('/lead-activity/store', [LeadController::class, 'storeActivity'])->name('lead.activity.store');

    Route::prefix('notifications')->group(function () {
        Route::get('/',              [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/{id}/read',    [NotificationController::class, 'markRead'])->name('notifications.markRead');
        Route::post('/read-all',     [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
        Route::get('/birthday-card', [NotificationController::class, 'checkBirthdayCard'])->name('notifications.birthdayCard');
    });

    Route::post('/employee/download/send-otp',   [EmployeeController::class, 'sendOtp'])->name('employee.download.sendOtp');
    Route::post('/employee/download/verify-otp', [EmployeeController::class, 'verifyAndDownload'])->name('employee.download.verify');
    Route::get('/employee/download/file',        [EmployeeController::class, 'downloadFile'])->name('employee.download.file');

    Route::prefix('payslip')->name('payslip.')->group(function () {
        Route::get('/',                      [PayslipController::class, 'index'])->name('index');
        Route::post('/store',                [PayslipController::class, 'store'])->name('store');
        Route::get('/previous/{employeeId}', [PayslipController::class, 'previous'])->name('previous');
        Route::get('/show/{id}',             [PayslipController::class, 'show'])->name('show');
        Route::get('/employee-data/{id}',    [PayslipController::class, 'getEmployeeData'])->name('employee.data');
        Route::get('/secure-download/{id}',  [PayslipController::class, 'secureDownload'])->name('secure.download');
    });

    Route::prefix('job-positions')->group(function () {
        Route::get('/', [JobPositionController::class, 'index'])->name('job_positions.index');
        Route::get('/create', [JobPositionController::class, 'create'])->name('job_positions.create');
        Route::post('/store', [JobPositionController::class, 'store'])->name('job_positions.store');
    });


    Route::get('/policies',               [PolicyController::class, 'index'])->name('policies.index');
    Route::get('/policies/create',        [PolicyController::class, 'create'])->name('policies.create');
    Route::post('/policies',              [PolicyController::class, 'store'])->name('policies.store');
    Route::get('/policies/{id}/edit',     [PolicyController::class, 'edit'])->name('policies.edit');
    Route::put('/policies/{id}',          [PolicyController::class, 'update'])->name('policies.update');
    Route::patch('/policies/{id}/toggle', [PolicyController::class, 'toggleStatus'])->name('policies.toggle');
});
