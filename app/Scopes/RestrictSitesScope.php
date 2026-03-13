<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class RestrictSitesScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        // Skip restrictions for settings routes
        if ($this->isSettingsRoute()) {
            return;
        }

        // Configurable admin bypass (default false)
        $adminBypass = filter_var(env('SITE_ACCESS_ADMIN_BYPASS', false), FILTER_VALIDATE_BOOL);
        if ($adminBypass && method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return;
        }

        $ids = method_exists($user, 'getAccessibleSiteIds')
            ? $user->getAccessibleSiteIds()
            : collect([]);

        $ids = $ids->values()->all();

        if (empty($ids)) {
            $builder->whereRaw('1 = 0');
            return;
        }

        $builder->whereIn($model->getTable() . '.id', $ids);
    }

    /**
     * Check if the current request is for a settings route
     */
    private function isSettingsRoute(): bool
    {
        $currentRouteName = Request::route()?->getName();

        if (!$currentRouteName) {
            return false;
        }

        // Check if route starts with 'settings.'
        return str_starts_with($currentRouteName, 'settings.');
    }
}
