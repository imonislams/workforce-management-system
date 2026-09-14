<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(protected AttendanceService $attendanceService) {}

    public function index(Request $request): View
    {
        $query = Attendance::with(['employee.department', 'employee.designation']);

        if ($request->filled('date')) {
            $query->where('date', $request->input('date'));
        } else {
            $query->where('date', now()->toDateString());
        }

        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->input('department_id'));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $records = $query->latest('date')->paginate(15)->withQueryString();
        $departments = Department::where('status', 'active')->get();

        return view('admin.attendance.index', compact('records', 'departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'date' => ['required', 'date'],
            'check_in' => ['nullable', 'date_format:H:i'],
            'check_out' => ['nullable', 'date_format:H:i', 'after:check_in'],
            'status' => ['required', 'in:present,late,absent,half_day,leave,holiday'],
            'notes' => ['nullable', 'string'],
        ]);

        $checkInDateTime = $validated['check_in'] ? $validated['date'] . ' ' . $validated['check_in'] . ':00' : null;
        $checkOutDateTime = $validated['check_out'] ? $validated['date'] . ' ' . $validated['check_out'] . ':00' : null;

        $workingHours = 0;
        if ($checkInDateTime && $checkOutDateTime) {
            $workingMinutes = \Carbon\Carbon::parse($checkInDateTime)->diffInMinutes(\Carbon\Carbon::parse($checkOutDateTime));
            $workingHours = round($workingMinutes / 60, 2);
        }

        Attendance::updateOrCreate(
            ['employee_id' => $validated['employee_id'], 'date' => $validated['date']],
            [
                'check_in' => $checkInDateTime,
                'check_out' => $checkOutDateTime,
                'status' => $validated['status'],
                'working_hours' => $workingHours,
                'notes' => $validated['notes'],
            ]
        );

        return redirect()->back()->with('success', 'Attendance record saved successfully.');
    }
}
