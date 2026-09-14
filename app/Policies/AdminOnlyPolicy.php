<?php

namespace App\Policies;

use App\Models\User;

class AdminOnlyPolicy
{
    public function before(User $user): bool
    {
        return $user->isAdmin();
    }
}
