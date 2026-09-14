<?php

namespace App\Policies;

use App\Models\Leave;
use App\Models\User;

class LeavePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Leave $leave): bool
    {
        return $user->isAdmin() || ($user->employee && $user->employee->id === $leave->employee_id);
    }

    public function create(User $user): bool
    {
        return $user->isEmployee() && $user->employee !== null;
    }

    public function cancel(User $user, Leave $leave): bool
    {
        return $user->isEmployee() && $user->employee && $user->employee->id === $leave->employee_id && $leave->status === 'pending';
    }

    public function approveOrReject(User $user): bool
    {
        return $user->isAdmin();
    }
}
