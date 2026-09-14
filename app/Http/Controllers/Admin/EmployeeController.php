<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\WorkSchedule;
use App\Services\EmployeeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function __construct(protected EmployeeService $employeeService) {}

    public function index(Request $request): View
    {
        $query = Employee::with(['department', 'designation', 'workSchedule']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('employee_code', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        if ($request->filled('status')) {
            $query->where('employment_status', $request->input('status'));
        }

        $employees = $query->latest()->paginate(10)->withQueryString();
        $departments = Department::where('status', 'active')->get();

        return view('admin.employees.index', compact('employees', 'departments'));
    }

    public function create(): View
    {
        $departments = Department::where('status', 'active')->get();
        $designations = Designation::where('status', 'active')->get();
        $schedules = WorkSchedule::where('status', 'active')->get();
        $generatedCode = $this->employeeService->generateEmployeeCode();

        return view('admin.employees.create', compact('departments', 'designations', 'schedules', 'generatedCode'));
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $this->employeeService->createEmployee(
            $request->validated(),
            $request->file('photo')
        );

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee): View
    {
        $employee->load(['department', 'designation', 'workSchedule', 'user', 'attendance' => fn ($q) => $q->latest()->limit(10)]);

        return view('admin.employees.show', compact('employee'));
    }

    public function edit(Employee $employee): View
    {
        $departments = Department::where('status', 'active')->get();
        $designations = Designation::where('status', 'active')->get();
        $schedules = WorkSchedule::where('status', 'active')->get();

        return view('admin.employees.edit', compact('employee', 'departments', 'designations', 'schedules'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $this->employeeService->updateEmployee(
            $employee,
            $request->validated(),
            $request->file('photo')
        );

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        if ($employee->user) {
            $employee->user->delete();
        }
        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    public function toggleStatus(Employee $employee): RedirectResponse
    {
        $this->employeeService->toggleStatus($employee);

        return redirect()->back()->with('success', 'Employee status updated successfully.');
    }
}
