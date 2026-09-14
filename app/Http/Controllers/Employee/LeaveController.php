<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Services\LeaveService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveController extends Controller
{
    public function __construct(protected LeaveService $leaveService) {}

    public function index(): View
    {
        $employee = auth()->user()->employee;
        $leaveTypes = LeaveType::where('status', 'active')->get();
        $leaves = Leave::with('leaveType')
            ->where('employee_id', $employee->id)
            ->latest()
            ->paginate(15);

        return view('employee.leaves.index', compact('employee', 'leaveTypes', 'leaves'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string'],
        ]);

        $employee = auth()->user()->employee;

        try {
            $this->leaveService->applyLeave($employee, $request->all());
            return redirect()->back()->with('success', 'Leave application submitted successfully.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function cancel(Leave $leave): RedirectResponse
    {
        $employee = auth()->user()->employee;

        try {
            $this->leaveService->cancelLeave($leave, $employee);
            return redirect()->back()->with('success', 'Leave request cancelled successfully.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
