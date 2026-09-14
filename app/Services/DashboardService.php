<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;

class DashboardService
{
    public function getAdminMetrics(): array
    {
        $today = Carbon::today()->format('Y-m-d');

        $totalEmployees = Employee::where('employment_status', 'active')->count();
        $presentToday = Attendance::where('date', $today)->whereIn('status', ['present', 'late'])->count();
        $absentToday = Attendance::where('date', $today)->where('status', 'absent')->count();
        $lateToday = Attendance::where('date', $today)->where('status', 'late')->count();
        $onLeaveToday = Leave::where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();
        $departmentsCount = Department::where('status', 'active')->count();

        $recentAttendance = Attendance::with('employee')
            ->where('date', $today)
            ->latest('updated_at')
            ->limit(5)
            ->get();

        $pendingLeaves = Leave::with(['employee', 'leaveType'])
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return compact(
            'totalEmployees',
            'presentToday',
            'absentToday',
            'lateToday',
            'onLeaveToday',
            'departmentsCount',
            'recentAttendance',
            'pendingLeaves'
        );
    }

    public function getEmployeeMetrics(?Employee $employee): array
    {
        if (! $employee) {
            return [
                'todayAttendance' => null,
                'presentDays' => 0,
                'lateDays' => 0,
                'absentDays' => 0,
                'leaveDays' => 0,
                'totalWorkingHours' => 0,
            ];
        }

        $today = Carbon::today()->format('Y-m-d');
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();

        $monthlyAttendance = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();

        $presentDays = $monthlyAttendance->whereIn('status', ['present', 'late'])->count();
        $lateDays = $monthlyAttendance->where('status', 'late')->count();
        $absentDays = $monthlyAttendance->where('status', 'absent')->count();
        $totalWorkingHours = $monthlyAttendance->sum('working_hours');

        $leaveDays = Leave::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->whereBetween('start_date', [$startOfMonth, $endOfMonth])
            ->sum('total_days');

        return compact(
            'todayAttendance',
            'presentDays',
            'lateDays',
            'absentDays',
            'leaveDays',
            'totalWorkingHours'
        );
    }
}
