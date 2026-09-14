<?php

namespace App\Services;

use App\Models\Attendance;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportService
{
    public function getAttendanceReport(array $filters, bool $paginate = true)
    {
        $query = Attendance::with(['employee.department', 'employee.designation']);

        if (! empty($filters['start_date']) && ! empty($filters['end_date'])) {
            $query->whereBetween('date', [$filters['start_date'], $filters['end_date']]);
        }

        if (! empty($filters['department_id'])) {
            $query->whereHas('employee', function ($q) use ($filters) {
                $q->where('department_id', $filters['department_id']);
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $paginate
            ? $query->latest('date')->paginate(20)->withQueryString()
            : $query->latest('date')->get();
    }

    public function exportCsv(string $filename, array $headers, array $data): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($headers, $data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            foreach ($data as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
