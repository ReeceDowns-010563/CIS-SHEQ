<?php

namespace App\Policies;

use App\Models\EmailAuditLog;
use App\Models\User;

class EmailAuditLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'email_manager']);
    }

    public function view(User $user, EmailAuditLog $emailAuditLog): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'email_manager']);
    }

    public function export(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }
}
