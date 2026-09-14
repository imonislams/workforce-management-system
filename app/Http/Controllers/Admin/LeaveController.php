<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Services\LeaveService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveController extends Controller
{
    public function __construct(protected LeaveService $leaveService) {}

    public function index(Request $request): View
    {
        $query = Leave::with(['employee.department', 'leaveType', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $leaves = $query->latest()->paginate(15)->withQueryString();

        return view('admin.leaves.index', compact('leaves'));
    }

    public function approve(Request $request, Leave $leave): RedirectResponse
    {
        $this->leaveService->approveLeave($leave, auth()->user(), $request->input('admin_note'));
        return redirect()->back()->with('success', 'Leave application approved.');
    }

    public function reject(Request $request, Leave $leave): RedirectResponse
    {
        $this->leaveService->rejectLeave($leave, auth()->user(), $request->input('admin_note'));
        return redirect()->back()->with('success', 'Leave application rejected.');
    }
}
