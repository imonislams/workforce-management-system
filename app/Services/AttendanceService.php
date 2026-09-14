<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Setting;
use App\Models\WorkSchedule;
use Carbon\Carbon;

class AttendanceService
{
    public function checkIn(Employee $employee, array $data = []): Attendance
    {
        $now = isset($data['check_in_time']) ? Carbon::parse($data['check_in_time']) : Carbon::now();
        $date = $now->format('Y-m-d');

        $existing = Attendance::where('employee_id', $employee->id)
            ->where('date', $date)
            ->first();

        if ($existing) {
            throw new \InvalidArgumentException('Attendance check-in record already exists for today.');
        }

        $schedule = $employee->workSchedule;

        $gracePeriod = $schedule ? $schedule->grace_period : ((int) Setting::where('key', 'grace_period')->value('value') ?: 15);
        $startTimeStr = $schedule ? $schedule->start_time : (Setting::where('key', 'office_start_time')->value('value') ?: '09:00:00');

        $scheduledStart = Carbon::parse($date . ' ' . $startTimeStr);
        $lateThreshold = (clone $scheduledStart)->addMinutes($gracePeriod);

        $lateMinutes = 0;
        $status = 'present';

        if ($now->greaterThan($lateThreshold)) {
            $status = 'late';
            $lateMinutes = (int) $scheduledStart->diffInMinutes($now);
        }

        try {
            return Attendance::create([
                'employee_id' => $employee->id,
                'date' => $date,
                'check_in' => $now->format('Y-m-d H:i:s'),
                'status' => $status,
                'late_minutes' => $lateMinutes,
                'notes' => $data['notes'] ?? null,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            throw new \InvalidArgumentException('Attendance check-in record already exists for today.');
        }
    }

    public function checkOut(Attendance $attendance, array $data = []): Attendance
    {
        if ($attendance->check_out !== null) {
            throw new \InvalidArgumentException('Attendance has already been checked out.');
        }

        $now = isset($data['check_out_time']) ? Carbon::parse($data['check_out_time']) : Carbon::now();
        $rawCheckIn = $attendance->getRawOriginal('check_in');
        $checkInTime = Carbon::parse($rawCheckIn);

        if ($now->lessThanOrEqualTo($checkInTime)) {
            throw new \InvalidArgumentException('Check-out time must be after check-in time.');
        }

        $employee = $attendance->employee;
        $schedule = $employee ? $employee->workSchedule : null;

        $dateStr = Carbon::parse($attendance->getRawOriginal('date'))->format('Y-m-d');
        $endTimeStr = $schedule ? $schedule->end_time : (Setting::where('key', 'office_end_time')->value('value') ?: '17:00:00');
        $scheduledEnd = Carbon::parse($dateStr . ' ' . $endTimeStr);

        $earlyLeaveMinutes = 0;
        $overtimeMinutes = 0;

        if ($now->lessThan($scheduledEnd)) {
            $earlyLeaveMinutes = (int) $now->diffInMinutes($scheduledEnd);
        } elseif ($now->greaterThan($scheduledEnd)) {
            $overtimeMinutes = (int) $scheduledEnd->diffInMinutes($now);
        }

        $workingMinutes = $checkInTime->diffInMinutes($now);
        $workingHours = round($workingMinutes / 60, 2);

        $attendance->update([
            'check_out' => $now->format('Y-m-d H:i:s'),
            'early_leave_minutes' => $earlyLeaveMinutes,
            'overtime_minutes' => $overtimeMinutes,
            'working_hours' => $workingHours,
            'notes' => isset($data['notes']) ? trim(($attendance->notes ? $attendance->notes . ' | ' : '') . $data['notes']) : $attendance->notes,
        ]);

        return $attendance;
    }

    public function getTodayAttendance(Employee $employee): ?Attendance
    {
        return Attendance::where('employee_id', $employee->id)
            ->where('date', Carbon::today()->format('Y-m-d'))
            ->first();
    }
}
