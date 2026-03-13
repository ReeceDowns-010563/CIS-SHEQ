<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * SiteScope provides a global query scope to enforce user-based site access.
 *
 * This scope automatically filters queries for models that have a `site_id`,
 * ensuring that users can only view data from the sites they are
 * authorized to access. This provides a robust, application-wide security
 * layer, preventing accidental data leaks.
 *
 * The scope performs the following checks:
 * 1. Retrieves the currently authenticated user.
 * 2. If no user is authenticated (e.g., in console Commands), no filtering is applied.
 * 3. If the user is an administrator, no filtering is applied, granting them full access.
 * 4. For regular users, it fetches the list of accessible site IDs.
 * 5. It applies a `whereIn('site_id', ...)` clause to the query, restricting results
 *    to only the sites the user has permission to view.
 */
class SiteScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        // Get the currently authenticated user.
        $user = Auth::user();

        // Do not apply the scope if there is no authenticated user (e.g., console Commands)
        // or if the user is an administrator, who should have unrestricted access.
        if (!$user || $user->isAdmin()) {
            return;
        }

        // Retrieve the list of site IDs the user is authorized to access.
        // This method centralizes the complex access logic from the User model.
        $accessibleSiteIds = $user->getAccessibleSiteIds();

        // Apply the constraint to the query, restricting results to the user's accessible sites.
        $builder->whereIn($model->getTable() . '.site_id', $accessibleSiteIds);
    }
}
