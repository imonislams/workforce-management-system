<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct(protected ReportService $reportService) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['start_date', 'end_date', 'department_id', 'status', 'type']);
        $records = $this->reportService->getAttendanceReport($filters, true);
        $departments = Department::where('status', 'active')->get();

        return view('admin.reports.index', compact('records', 'departments', 'filters'));
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only(['start_date', 'end_date', 'department_id', 'status', 'type']);
        $records = $this->reportService->getAttendanceReport($filters, false);

        $headers = ['Employee Code', 'Employee Name', 'Department', 'Date', 'Check In', 'Check Out', 'Status', 'Working Hours', 'Late Minutes'];
        $data = [];

        foreach ($records as $record) {
            $data[] = [
                $record->employee->employee_code ?? '',
                $record->employee->full_name ?? '',
                $record->employee->department->name ?? '',
                $record->date ? $record->date->format('Y-m-d') : '',
                $record->check_in ? $record->check_in->format('H:i:s') : '',
                $record->check_out ? $record->check_out->format('H:i:s') : '',
                ucfirst($record->status),
                $record->working_hours,
                $record->late_minutes,
            ];
        }

        return $this->reportService->exportCsv('attendance_report_' . now()->format('Ymd_His') . '.csv', $headers, $data);
    }
}
