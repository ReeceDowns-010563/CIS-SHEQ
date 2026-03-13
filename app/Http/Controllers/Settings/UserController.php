<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Role;
use App\Models\Site;
use App\Models\User;
use App\Models\UserAccessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Handle unarchive action
        if ($request->has('unarchive')) {
            $user = User::withoutGlobalScope('active')->findOrFail($request->get('unarchive'));

            // Check if user is admin for unarchive permission
            if (!auth()->user()->isAdmin()) {
                return redirect()->route('settings.users.index')
                    ->with('error', 'You do not have permission to unarchive users.');
            }

            $user->unarchive();
            return redirect()->route('settings.users.index')
                ->with('success', 'User has been unarchived and reactivated.');
        }

        $query = User::with('role');

        // Show archived users if requested
        if ($request->filled('show_archived') && $request->boolean('show_archived')) {
            $query = User::withoutGlobalScope('active')->archived();
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter by role name
        if ($request->filled('role')) {
            $roleName = $request->role;
            $query->whereHas('role', function ($q) use ($roleName) {
                $q->where('name', $roleName);
            });
        }

        // Sorting functionality
        $sortField = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');
        $allowedSortFields = ['name', 'email', 'created_at', 'role'];

        if (! in_array($sortField, $allowedSortFields)) {
            $sortField = 'name';
        }

        if (! in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        if ($sortField === 'role') {
            // sort by related role name
            $query->select('users.*')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->orderBy('roles.name', $sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $users = $query->paginate(15)->withQueryString();

        // Optional: pass list of role names for filter UI
        $roles = Role::orderBy('name')->get();

        return view('settings.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        $branches = Branch::with('sites')->orderBy('branch_name')->get();

        // Load ALL sites with their branches (removed active filter)
        $sites = Site::with('branch')->orderBy('name')->get();

        // Prepare data for JavaScript
        $branchesData = $branches->map(function ($branch) {
            return [
                'id' => $branch->id,
                'name' => $branch->display_name,
                'code' => $branch->branch_code,
            ];
        });

        $sitesData = $sites->map(function ($site) {
            return [
                'id' => $site->id,
                'name' => $site->name,
                'branch_id' => $site->branch_id,
                'branch_name' => $site->branch ? $site->branch->display_name : 'Unknown Branch',
                'display_name' => $site->display_name,
            ];
        });

        return view('settings.users.create', compact('roles', 'branches', 'sites', 'branchesData', 'sitesData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'all_branches' => 'boolean',
            'all_sites' => 'boolean',
            'branches' => 'array',
            'branches.*' => 'exists:branches,id',
            'sites' => 'array',
            'sites.*' => 'exists:sites,id',
        ]);

        DB::transaction(function () use ($request) {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'password' => Hash::make($request->password),
            ]);

            // Create access settings
            UserAccessSetting::create([
                'user_id' => $user->id,
                'all_branches' => $request->boolean('all_branches'),
                'all_sites' => $request->boolean('all_sites'),
            ]);

            // Sync branches
            $branches = $request->get('branches', []);
            $user->accessibleBranches()->sync($branches);

            // Sync sites and auto-add their branches
            $sites = $request->get('sites', []);
            $user->accessibleSites()->sync($sites);

            // Auto-add branches for selected sites
            if (!empty($sites)) {
                $siteBranchIds = Site::whereIn('id', $sites)->pluck('branch_id')->unique();
                $existingBranchIds = collect($branches);
                $newBranchIds = $siteBranchIds->diff($existingBranchIds);

                foreach ($newBranchIds as $branchId) {
                    $user->accessibleBranches()->attach($branchId, ['all_sites_in_branch' => false]);
                }
            }
        });

        return redirect()->route('settings.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $branches = Branch::with('sites')->orderBy('branch_name')->get();

        // Load ALL sites with their branches (removed active filter)
        $sites = Site::with('branch')->orderBy('name')->get();

        // Load user's current access settings
        $user->load(['accessSettings', 'accessibleBranches', 'accessibleSites.branch']);

        // Prepare data for JavaScript
        $branchesData = $branches->map(function ($branch) {
            return [
                'id' => $branch->id,
                'name' => $branch->display_name,
                'code' => $branch->branch_code,
            ];
        });

        $sitesData = $sites->map(function ($site) {
            return [
                'id' => $site->id,
                'name' => $site->name,
                'branch_id' => $site->branch_id,
                'branch_name' => $site->branch ? $site->branch->display_name : 'Unknown Branch',
                'display_name' => $site->display_name,
            ];
        });

        return view('settings.users.edit', compact('user', 'roles', 'branches', 'sites', 'branchesData', 'sitesData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'all_branches' => 'boolean',
            'all_sites' => 'boolean',
            'branches' => 'array',
            'branches.*' => 'exists:branches,id',
            'sites' => 'array',
            'sites.*' => 'exists:sites,id',
        ]);

        DB::transaction(function () use ($request, $user) {
            // Update user basic info
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // Update or create access settings
            $accessSettings = $user->accessSettings ?: new UserAccessSetting();
            $accessSettings->user_id = $user->id;
            $accessSettings->all_branches = $request->boolean('all_branches');
            $accessSettings->all_sites = $request->boolean('all_sites');
            $accessSettings->save();

            // Clear the cached site access since we're updating permissions
            $this->clearUserSiteAccessCache($user);

            // Get the form data
            $branches = $request->get('branches', []);
            $sites = $request->get('sites', []);

            // Sync branches first
            $user->accessibleBranches()->sync($branches);

            // Sync sites
            $user->accessibleSites()->sync($sites);

            // Auto-add branches for selected sites (only if sites are explicitly selected)
            if (!empty($sites)) {
                // Get branch IDs for the selected sites
                $siteBranchIds = Site::withoutGlobalScopes()
                    ->whereIn('id', $sites)
                    ->pluck('branch_id')
                    ->unique();

                // Get current accessible branch IDs from the database (after sync)
                $currentBranchIds = $user->accessibleBranches()
                    ->pluck('branches.id')
                    ->unique();

                // Find branches that need to be added
                $newBranchIds = $siteBranchIds->diff($currentBranchIds);

                // Add the missing branches
                foreach ($newBranchIds as $branchId) {
                    $user->accessibleBranches()->attach($branchId, [
                        'all_sites_in_branch' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Force refresh the user's relationships to clear any cached data
            $user->refresh();
            $user->load(['accessSettings', 'accessibleBranches', 'accessibleSites']);

            // Clear the cached site access again after all updates
            $this->clearUserSiteAccessCache($user);
        });

        return redirect()->route('settings.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Clear the user's cached site access data
     */
    private function clearUserSiteAccessCache(User $user)
    {
        // Clear any static cache that might exist in the User model
        // This ensures the getAccessibleSiteIds method recalculates access
        $reflection = new \ReflectionClass($user);
        if ($reflection->hasProperty('accessibleSiteIds')) {
            $property = $reflection->getProperty('accessibleSiteIds');
            $property->setAccessible(true);
            $property->setValue($user, null);
        }

        // Also clear any relationship caches
        $user->unsetRelation('accessibleSites');
        $user->unsetRelation('accessibleBranches');
        $user->unsetRelation('accessSettings');
    }

    /**
     * Show the user access settings form.
     */
    public function editAccess(User $user)
    {
        $user->load(['accessSettings', 'accessibleBranches', 'accessibleSites.branch']);

        $branches = Branch::with('sites')->orderBy('branch_name')->get();
        $sites = Site::with('branch')->orderBy('name')->get();

        return view('settings.users.access', compact('user', 'branches', 'sites'));
    }

    /**
     * Update user access settings.
     */
    public function updateAccess(Request $request, User $user)
    {
        $request->validate([
            'all_branches' => 'boolean',
            'all_sites' => 'boolean',
            'branches' => 'array',
            'branches.*' => 'exists:branches,id',
            'sites' => 'array',
            'sites.*' => 'exists:sites,id',
        ]);

        DB::transaction(function () use ($request, $user) {
            // Update or create access settings
            $accessSettings = $user->accessSettings ?: new UserAccessSetting();
            $accessSettings->user_id = $user->id;
            $accessSettings->all_branches = $request->boolean('all_branches');
            $accessSettings->all_sites = $request->boolean('all_sites');
            $accessSettings->save();

            // Clear cached data
            $this->clearUserSiteAccessCache($user);

            // Sync branches
            $branches = $request->get('branches', []);
            $user->accessibleBranches()->sync($branches);

            // Sync sites and auto-add their branches
            $sites = $request->get('sites', []);
            $user->accessibleSites()->sync($sites);

            // Auto-add branches for selected sites
            if (!empty($sites)) {
                $siteBranchIds = Site::withoutGlobalScopes()
                    ->whereIn('id', $sites)
                    ->pluck('branch_id')
                    ->unique();

                $existingBranchIds = $user->accessibleBranches()->pluck('branches.id');
                $newBranchIds = $siteBranchIds->diff($existingBranchIds);

                foreach ($newBranchIds as $branchId) {
                    $user->accessibleBranches()->attach($branchId, [
                        'all_sites_in_branch' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Clear cached data again after updates
            $this->clearUserSiteAccessCache($user);
        });

        return response()->json([
            'success' => true,
            'message' => 'Access settings updated successfully.'
        ]);
    }

    /**
     * Get branches and sites data for AJAX requests.
     */
    public function getAccessData(Request $request)
    {
        $search = $request->get('search', '');

        $branches = Branch::when($search, function ($query, $search) {
            $query->search($search);
        })->orderBy('branch_name')->get()->map(function ($branch) {
            return [
                'id' => $branch->id,
                'name' => $branch->display_name,
                'code' => $branch->branch_code,
            ];
        });

        $sites = Site::with('branch')
            ->when($search, function ($query, $search) {
                $query->search($search);
            })
            ->orderBy('name')
            ->get()
            ->map(function ($site) {
                return [
                    'id' => $site->id,
                    'name' => $site->name,
                    'branch_id' => $site->branch_id,
                    'branch_name' => $site->branch ? $site->branch->display_name : 'Unknown Branch',
                    'display_name' => $site->display_name,
                ];
            });

        return response()->json([
            'branches' => $branches,
            'sites' => $sites,
        ]);
    }

    /**
     * Archive the specified user (instead of deleting).
     */
    public function destroy(User $user)
    {
        // Prevent archiving of the last admin user
        $adminCount = User::whereHas('role', function ($q) {
            $q->where('name', 'admin');
        })->count();

        if ($user->isAdmin() && $adminCount <= 1) {
            return redirect()->route('settings.users.index')
                ->with('error', 'Cannot archive the last administrator user.');
        }

        // Prevent users from archiving themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('settings.users.index')
                ->with('error', 'You cannot archive your own account.');
        }

        $user->archive();

        return redirect()->route('settings.users.index')
            ->with('success', 'User archived successfully. Their data has been preserved.');
    }
}
