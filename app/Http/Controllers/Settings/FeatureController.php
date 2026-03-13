<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Manages feature flags / toggles and their role assignments.
 * Supports creation, editing, deletion and optional audit logging of changes.
 */
class FeatureController extends Controller
{
    public function __construct()
    {
        // Uncomment the following line to restrict management of features to admins only.
        // $this->middleware('admin');
    }

    /**
     * List all features with their associated roles, paginated.
     */
    public function index(): View
    {
        $features = Feature::with('roles')->orderBy('name')->paginate(15);
        return view('settings.features.index', compact('features'));
    }

    /**
     * Show form to create a new feature.
     */
    public function create(): View
    {
        $roles = Role::orderBy('name')->get();
        return view('settings.features.create', compact('roles'));
    }

    /**
     * Persist a new feature and sync its allowed roles.
     * Optionally logs the creation if a SecurityLog model exists.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'key' => ['required', 'alpha_dash', 'unique:features,key'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $feature = Feature::create([
            'key' => strtolower($data['key']),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $feature->roles()->sync($data['roles'] ?? []);

        if (class_exists(\App\Models\SecurityLog::class)) {
            \App\Models\SecurityLog::create([
                'user_id' => Auth::id(),
                'action' => 'feature_created',
                'details' => "Created feature '{$feature->key}' with roles: " . implode(',', $data['roles'] ?? []),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return redirect()->route('settings.features.index')->with('success', 'Feature created.');
    }

    /**
     * Show form to edit an existing feature, including its role associations.
     */
    public function edit(Feature $feature): View
    {
        $roles = Role::orderBy('name')->get();
        $feature->load('roles');
        return view('settings.features.edit', compact('feature', 'roles'));
    }

    /**
     * Apply updates to a feature, sync role assignments, and log diffs if applicable.
     */
    public function update(Request $request, Feature $feature): RedirectResponse
    {
        $data = $request->validate([
            'key' => ['required', 'alpha_dash', Rule::unique('features', 'key')->ignore($feature->id)],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $oldRoles = $feature->roles()->pluck('id')->toArray();

        $feature->update([
            'key' => strtolower($data['key']),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $feature->roles()->sync($data['roles'] ?? []);

        if (class_exists(\App\Models\SecurityLog::class)) {
            $added = array_diff($data['roles'] ?? [], $oldRoles);
            $removed = array_diff($oldRoles, $data['roles'] ?? []);
            $details = "Updated feature '{$feature->key}'.";
            if ($added) {
                $details .= " Added roles: " . implode(',', $added) . ".";
            }
            if ($removed) {
                $details .= " Removed roles: " . implode(',', $removed) . ".";
            }

            \App\Models\SecurityLog::create([
                'user_id' => Auth::id(),
                'action' => 'feature_updated',
                'details' => $details,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return redirect()->route('settings.features.index')->with('success', 'Feature updated.');
    }

    /**
     * Remove a feature and detach all its role relationships.
     * Logs deletion if the SecurityLog model exists.
     */
    public function destroy(Feature $feature): RedirectResponse
    {
        $key = $feature->key;
        $feature->roles()->detach();
        $feature->delete();

        if (class_exists(\App\Models\SecurityLog::class)) {
            \App\Models\SecurityLog::create([
                'user_id' => Auth::id(),
                'action' => 'feature_deleted',
                'details' => "Deleted feature '{$key}'.",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }

        return back()->with('success', 'Feature deleted.');
    }
}
