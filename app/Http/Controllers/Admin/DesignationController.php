<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDesignationRequest;
use App\Http\Requests\UpdateDesignationRequest;
use App\Models\Designation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Designation::withCount('employees');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $designations = $query->latest()->paginate(10)->withQueryString();

        return view('admin.designations.index', compact('designations'));
    }

    public function create(): View
    {
        return view('admin.designations.create');
    }

    public function store(StoreDesignationRequest $request): RedirectResponse
    {
        Designation::create($request->validated());

        return redirect()->route('admin.designations.index')
            ->with('success', 'Designation created successfully.');
    }

    public function edit(Designation $designation): View
    {
        return view('admin.designations.edit', compact('designation'));
    }

    public function update(UpdateDesignationRequest $request, Designation $designation): RedirectResponse
    {
        $designation->update($request->validated());

        return redirect()->route('admin.designations.index')
            ->with('success', 'Designation updated successfully.');
    }

    public function destroy(Designation $designation): RedirectResponse
    {
        $designation->delete();

        return redirect()->route('admin.designations.index')
            ->with('success', 'Designation deleted successfully.');
    }

    public function toggleStatus(Designation $designation): RedirectResponse
    {
        $newStatus = $designation->status === 'active' ? 'inactive' : 'active';
        $designation->update(['status' => $newStatus]);

        return redirect()->back()->with('success', "Designation status updated to {$newStatus}.");
    }
}
