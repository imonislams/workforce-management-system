<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {}

    public function __invoke(): View
    {
        $employee = auth()->user()->employee;
        $metrics = $this->dashboardService->getEmployeeMetrics($employee);
        return view('employee.dashboard', array_merge(['employee' => $employee], $metrics));
    }
}
