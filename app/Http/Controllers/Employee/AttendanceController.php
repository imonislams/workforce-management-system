<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(protected AttendanceService $attendanceService) {}

    public function index(): View
    {
        $employee = auth()->user()->employee;
        $todayStr = Carbon::now()->format('Y-m-d');
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $todayStr)
            ->first();

        $recentAttendance = Attendance::where('employee_id', $employee->id)
            ->latest('date')
            ->paginate(15);

        return view('employee.attendance.index', compact('employee', 'attendance', 'recentAttendance'));
    }

    public function checkIn(Request $request): RedirectResponse
    {
        $employee = auth()->user()->employee;

        try {
            $this->attendanceService->checkIn($employee, $request->only('notes'));
            return redirect()->back()->with('success', 'Checked in successfully!');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function checkOut(Request $request): RedirectResponse
    {
        $employee = auth()->user()->employee;
        $todayStr = Carbon::now()->format('Y-m-d');

        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $todayStr)
            ->first();

        if (! $attendance) {
            return redirect()->back()->with('error', 'No check-in record found for today.');
        }

        try {
            $this->attendanceService->checkOut($attendance, $request->only('notes'));
            return redirect()->back()->with('success', 'Checked out successfully!');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
