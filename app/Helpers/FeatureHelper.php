<?php

namespace App\Helpers;

use App\Models\Feature;
use Illuminate\Support\Facades\Auth;

class FeatureHelper
{
    /**
     * Check if the current authenticated user can access a feature
     */
    public static function canAccess(string $featureKey): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        $feature = Feature::where('key', $featureKey)->first();

        // If feature not defined, deny by default
        if (!$feature) {
            return false;
        }

        // If user has no role_id, deny
        $roleId = $user->role_id;
        if (!$roleId) {
            return false;
        }

        // Check if user's role is allowed for this feature
        return $feature->roles()->where('role_id', $roleId)->exists();
    }
}
