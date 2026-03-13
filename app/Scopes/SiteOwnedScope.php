<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * SiteOwnedScope
 *
 * Global scope that enforces site-based access for any model that has a site_id column.
 * - Admin bypass is disabled by default and can be enabled with SITE_ACCESS_ADMIN_BYPASS=true.
 * - Unauthenticated contexts (e.g., console) are not restricted.
 * - Non-admin users are restricted to the set of site_ids returned by User::getAccessibleSiteIds().
 *
 * Attach this scope to models that contain a site_id foreign key (e.g., Complaint, Employee, etc.).
 */
class SiteOwnedScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        // Do not restrict for console or guests
        if (!$user) {
            return;
        }

        // Configurable admin bypass (default false)
        $adminBypass = filter_var(env('SITE_ACCESS_ADMIN_BYPASS', false), FILTER_VALIDATE_BOOL);
        if ($adminBypass && method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return;
        }

        // Resolve allowed site IDs (fail-closed)
        $ids = method_exists($user, 'getAccessibleSiteIds')
            ? $user->getAccessibleSiteIds()
            : collect([]);

        $ids = $ids->values()->all();

        if (empty($ids)) {
            // No access => return no rows
            $builder->whereRaw('1 = 0');
            return;
        }

        $builder->whereIn($model->getTable() . '.site_id', $ids);
    }
}
