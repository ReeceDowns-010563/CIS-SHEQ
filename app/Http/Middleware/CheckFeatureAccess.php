<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Feature;

class CheckFeatureAccess
{
    public function handle(Request $request, Closure $next, string $featureKey)
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $feature = Feature::where('key', $featureKey)->first();

        // If feature not defined, deny by default
        if (! $feature) {
            abort(403);
        }

        // if user has no role_id or role relation, deny
        $roleId = $user->role_id;
        if (! $roleId) {
            abort(403);
        }

        $allowed = $feature->roles()->where('role_id', $roleId)->exists();

        if (! $allowed) {
            abort(403);
        }

        return $next($request);
    }
}
