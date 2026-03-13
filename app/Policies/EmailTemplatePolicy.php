<?php

namespace App\Policies;

use App\Models\EmailTemplate;
use App\Models\User;

class EmailTemplatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'email_manager']);
    }

    public function view(User $user, EmailTemplate $emailTemplate): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'email_manager']);
    }

    public function sendTest(User $user, EmailTemplate $emailTemplate): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'email_manager']);
    }
}
