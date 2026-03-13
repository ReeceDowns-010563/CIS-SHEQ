<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * CRUD management for roles. Provides listing, creation, editing, and safe deletion
 * with protection against removing roles that are currently assigned to users.
 */
class RolesController extends Controller
{
    /**
     * Display all roles with their user counts.
     */
    public function index(): View
    {
        // Eager load user counts for performance and order alphabetically for predictability.
        $roles = Role::withCount('users')->orderBy('name')->get();
        return view('settings.features.roles-index', compact('roles'));
    }

    /**
     * Show form to create a new role.
     */
    public function create(): View
    {
        return view('settings.features.roles-create');
    }

    /**
     * Validate input and persist a new role.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|alpha_dash|unique:roles,name',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        Role::create($validated);

        return redirect()->route('settings.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Show edit form for an existing role.
     */
    public function edit(Role $role): View
    {
        return view('settings.features.roles-edit', compact('role'));
    }

    /**
     * Validate and apply updates to a role.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|alpha_dash|unique:roles,name,' . $role->id,
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $role->update($validated);

        return redirect()->route('settings.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Safely delete a role if it has no associated users.
     */
    public function destroy(Role $role): RedirectResponse
    {
        if ($role->users()->exists()) {
            return back()->with('error', 'Cannot delete role: it is currently assigned to one or more users.');
        }

        $role->delete();

        return redirect()->route('settings.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
