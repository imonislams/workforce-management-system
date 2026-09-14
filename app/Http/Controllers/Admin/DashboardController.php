<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {}

    public function __invoke(): View
    {
        $metrics = $this->dashboardService->getAdminMetrics();
        return view('admin.dashboard', $metrics);
    }
}
