<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Attendance $attendance): bool
    {
        return $user->isAdmin() || ($user->employee && $user->employee->id === $attendance->employee_id);
    }

    public function checkIn(User $user): bool
    {
        return $user->isEmployee() && $user->employee !== null;
    }

    public function checkOut(User $user, Attendance $attendance): bool
    {
        return $user->isEmployee() && $user->employee && $user->employee->id === $attendance->employee_id;
    }

    public function manage(User $user): bool
    {
        return $user->isAdmin();
    }
}
