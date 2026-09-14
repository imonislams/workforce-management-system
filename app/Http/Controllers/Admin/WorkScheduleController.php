<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkScheduleRequest;
use App\Http\Requests\UpdateWorkScheduleRequest;
use App\Models\WorkSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $query = WorkSchedule::withCount('employees');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $schedules = $query->latest()->paginate(10)->withQueryString();

        return view('admin.work-schedules.index', compact('schedules'));
    }

    public function create(): View
    {
        return view('admin.work-schedules.create');
    }

    public function store(StoreWorkScheduleRequest $request): RedirectResponse
    {
        WorkSchedule::create($request->validated());

        return redirect()->route('admin.work-schedules.index')
            ->with('success', 'Work schedule created successfully.');
    }

    public function edit(WorkSchedule $workSchedule): View
    {
        return view('admin.work-schedules.edit', compact('workSchedule'));
    }

    public function update(UpdateWorkScheduleRequest $request, WorkSchedule $workSchedule): RedirectResponse
    {
        $workSchedule->update($request->validated());

        return redirect()->route('admin.work-schedules.index')
            ->with('success', 'Work schedule updated successfully.');
    }

    public function destroy(WorkSchedule $workSchedule): RedirectResponse
    {
        $workSchedule->delete();

        return redirect()->route('admin.work-schedules.index')
            ->with('success', 'Work schedule deleted successfully.');
    }

    public function toggleStatus(WorkSchedule $workSchedule): RedirectResponse
    {
        $newStatus = $workSchedule->status === 'active' ? 'inactive' : 'active';
        $workSchedule->update(['status' => $newStatus]);

        return redirect()->back()->with('success', "Work schedule status updated to {$newStatus}.");
    }
}
