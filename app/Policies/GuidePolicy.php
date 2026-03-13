<?php

namespace App\Policies;

use App\Models\Guide;
use App\Models\User;

class GuidePolicy
{
    public function view(User $user, Guide $guide)
    {
        return $user->id === $guide->created_by;
    }

    public function update(User $user, Guide $guide)
    {
        return $user->id === $guide->created_by;
    }

    public function delete(User $user, Guide $guide)
    {
        return $user->id === $guide->created_by;
    }
}
