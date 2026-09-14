<?php

use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\LeaveController as AdminLeaveController;
use App\Http\Controllers\Admin\LeaveTypeController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WorkScheduleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Employee\AttendanceController as EmployeeAttendanceController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\LeaveController as EmployeeLeaveController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'role.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');

    Route::patch('/departments/{department}/toggle-status', [DepartmentController::class, 'toggleStatus'])->name('departments.toggle-status');
    Route::resource('departments', DepartmentController::class)->except(['show']);

    Route::patch('/designations/{designation}/toggle-status', [DesignationController::class, 'toggleStatus'])->name('designations.toggle-status');
    Route::resource('designations', DesignationController::class)->except(['show']);

    Route::patch('/work-schedules/{workSchedule}/toggle-status', [WorkScheduleController::class, 'toggleStatus'])->name('work-schedules.toggle-status');
    Route::resource('work-schedules', WorkScheduleController::class)->except(['show']);

    Route::patch('/employees/{employee}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');
    Route::resource('employees', EmployeeController::class);

    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::patch('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::resource('users', UserController::class)->except(['show', 'destroy']);

    Route::get('/attendance', [AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AdminAttendanceController::class, 'store'])->name('attendance.store');

    Route::resource('leave-types', LeaveTypeController::class)->except(['create', 'edit', 'show']);
    Route::get('/leaves', [AdminLeaveController::class, 'index'])->name('leaves.index');
    Route::patch('/leaves/{leave}/approve', [AdminLeaveController::class, 'approve'])->name('leaves.approve');
    Route::patch('/leaves/{leave}/reject', [AdminLeaveController::class, 'reject'])->name('leaves.reject');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});

Route::middleware(['auth', 'role.employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', EmployeeDashboardController::class)->name('dashboard');

    Route::get('/attendance', [EmployeeAttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/check-in', [EmployeeAttendanceController::class, 'checkIn'])->name('check-in');
    Route::post('/check-out', [EmployeeAttendanceController::class, 'checkOut'])->name('check-out');

    Route::get('/leaves', [EmployeeLeaveController::class, 'index'])->name('leaves.index');
    Route::post('/leaves', [EmployeeLeaveController::class, 'store'])->name('leaves.store');
    Route::patch('/leaves/{leave}/cancel', [EmployeeLeaveController::class, 'cancel'])->name('leaves.cancel');
});
