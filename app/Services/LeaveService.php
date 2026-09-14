<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveService
{
    public function hasOverlappingLeave(int $employeeId, string $startDate, string $endDate, ?int $ignoreLeaveId = null): bool
    {
        $query = Leave::where('employee_id', $employeeId)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q2) use ($startDate, $endDate) {
                        $q2->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            });

        if ($ignoreLeaveId) {
            $query->where('id', '!=', $ignoreLeaveId);
        }

        return $query->exists();
    }

    public function applyLeave(Employee $employee, array $data): Leave
    {
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);

        if ($endDate->lessThan($startDate)) {
            throw new \InvalidArgumentException('End date cannot be earlier than start date.');
        }

        if ($this->hasOverlappingLeave($employee->id, $startDate->toDateString(), $endDate->toDateString())) {
            throw new \InvalidArgumentException('You already have a pending or approved leave application for these dates.');
        }

        $totalDays = $startDate->diffInDays($endDate) + 1;

        return Leave::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $data['leave_type_id'],
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'total_days' => $totalDays,
            'reason' => $data['reason'],
            'status' => 'pending',
        ]);
    }

    public function approveLeave(Leave $leave, User $admin, ?string $note = null): Leave
    {
        $leave->update([
            'status' => 'approved',
            'admin_note' => $note,
            'approved_by' => $admin->id,
            'approved_at' => now(),
        ]);

        return $leave;
    }

    public function rejectLeave(Leave $leave, User $admin, ?string $note = null): Leave
    {
        $leave->update([
            'status' => 'rejected',
            'admin_note' => $note,
            'approved_by' => $admin->id,
            'approved_at' => now(),
        ]);

        return $leave;
    }

    public function cancelLeave(Leave $leave, Employee $employee): Leave
    {
        if ($leave->employee_id !== $employee->id) {
            throw new \InvalidArgumentException('Unauthorized to cancel this leave.');
        }

        if ($leave->status !== 'pending') {
            throw new \InvalidArgumentException('Only pending leave requests can be cancelled.');
        }

        $leave->update(['status' => 'cancelled']);

        return $leave;
    }
}
