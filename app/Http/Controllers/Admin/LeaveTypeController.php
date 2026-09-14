<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LeaveTypeController extends Controller
{
    public function index(): View
    {
        $leaveTypes = LeaveType::latest()->paginate(10);
        return view('admin.leave-types.index', compact('leaveTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:leave_types,name'],
            'code' => ['required', 'string', 'max:50', 'unique:leave_types,code'],
            'max_days' => ['required', 'integer', 'min:1', 'max:365'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        LeaveType::create($validated);

        return redirect()->back()->with('success', 'Leave type created successfully.');
    }

    public function update(Request $request, LeaveType $leaveType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('leave_types', 'name')->ignore($leaveType->id)],
            'code' => ['required', 'string', 'max:50', Rule::unique('leave_types', 'code')->ignore($leaveType->id)],
            'max_days' => ['required', 'integer', 'min:1', 'max:365'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $leaveType->update($validated);

        return redirect()->back()->with('success', 'Leave type updated successfully.');
    }

    public function destroy(LeaveType $leaveType): RedirectResponse
    {
        $leaveType->delete();
        return redirect()->back()->with('success', 'Leave type deleted successfully.');
    }
}
