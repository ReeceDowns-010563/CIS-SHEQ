<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * Hidden attributes for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts for attributes.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'archived_at' => 'datetime',
        ];
    }

    // -----------------
    // Query Scopes
    // -----------------

    /**
     * Scope to include only active (non-archived) users.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('archived_at');
    }

    /**
     * Scope to include only archived users.
     */
    public function scopeArchived(Builder $query): Builder
    {
        return $query->whereNotNull('archived_at');
    }

    /**
     * Global scope to exclude archived users by default.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->whereNull('archived_at');
        });
    }

    // -----------------
    // Archive Methods
    // -----------------

    /**
     * Archive the user (soft archive instead of deletion).
     */
    public function archive(): bool
    {
        $this->archived_at = now();
        return $this->save();
    }

    /**
     * Unarchive the user.
     */
    public function unarchive(): bool
    {
        $this->archived_at = null;
        return $this->save();
    }

    /**
     * Check if the user is archived.
     */
    public function isArchived(): bool
    {
        return !is_null($this->archived_at);
    }

    /**
     * Check if the user is active (not archived).
     */
    public function isActive(): bool
    {
        return is_null($this->archived_at);
    }

    // -----------------
    // Relationships
    // -----------------

    /**
     * The user's role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * User site/branch access flags.
     */
    public function accessSettings(): HasOne
    {
        return $this->hasOne(UserAccessSetting::class);
    }

    /**
     * Branches this user may access.
     */
    public function accessibleBranches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'user_branch_access')
            ->withPivot('all_sites_in_branch')
            ->withTimestamps();
    }

    /**
     * Sites this user may access (explicitly selected).
     */
    public function accessibleSites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class, 'user_site_access')->withTimestamps();
    }

    // -----------------
    // Role helpers
    // -----------------

    /**
     * Convenient role name accessor.
     */
    public function getRoleNameAttribute(): ?string
    {
        return $this->role?->name;
    }

    /**
     * Check if the user has the specified role (case-insensitive).
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roleName
            ? strcasecmp($this->roleName, $roleName) === 0
            : false;
    }

    /**
     * Check if the user has any of the specified roles (case-insensitive).
     */
    public function hasAnyRole(array $roleNames): bool
    {
        if (!$this->roleName) {
            return false;
        }

        foreach ($roleNames as $roleName) {
            if (strcasecmp($this->roleName, $roleName) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user is an administrator.
     * Updated to check role_id = 1.
     */
    public function isAdmin(): bool
    {
        return $this->role_id === 1; // Assuming role_id 1 is always admin
    }

    // -----------------
    // Access flags
    // -----------------

    /**
     * Is user allowed to all branches?
     */
    public function hasAccessToAllBranches(): bool
    {
        return $this->accessSettings?->all_branches ?? false;
    }

    /**
     * Is user allowed to all sites?
     */
    public function hasAccessToAllSites(): bool
    {
        return $this->accessSettings?->all_sites ?? false;
    }

    /**
     * User's dashboard preferences.
     */
    public function dashboardPreference(): HasOne
    {
        return $this->hasOne(UserDashboardPreference::class);
    }

    // -----------------
    // Core site access resolver
    // -----------------

    /**
     * Resolve the list of site IDs this user may access.
     *
     * IMPORTANT:
     * - Uses raw DB queries to avoid triggering global scopes on Site and prevent recursion.
     * - Fails closed (empty collection) if no access grants exist.
     * - Highest precedence: explicitly selected sites.
     * - Then: "All Sites" + scope of branches (or entire system if "All Branches").
     * - Then: selected branches only.
     */
    public function getAccessibleSiteIds(): Collection
    {
        // Cache by user to avoid repeated queries in a single request.
        static $cache = [];

        if (isset($cache[$this->id])) {
            return $cache[$this->id];
        }



        // Eager load relationships needed to compute access
        $this->loadMissing([
            'accessSettings',
            'accessibleBranches:id',
            'accessibleSites:id',
        ]);
        
        

        // 1) Specific sites explicitly selected (highest precedence).
        if ($this->accessibleSites->isNotEmpty()) {
            return $cache[$this->id] = $this->accessibleSites
                ->pluck('id')
                ->unique()
                ->values();
        }
        
        

        // 2) "All Sites" ON.
        if ($this->hasAccessToAllSites()) {
            if ($this->hasAccessToAllBranches()) {
                // All sites in the system (no global scopes to avoid recursion).
                $ids = DB::table('sites')->where('active', 1)->pluck('id');
                return $cache[$this->id] = $ids->unique()->values();
            }

            // All sites within user's selected branches.
            $branchIds = $this->accessibleBranches->pluck('id')->unique()->values();

            if ($branchIds->isNotEmpty()) {
                $ids = DB::table('sites')
                    ->whereIn('branch_id', $branchIds)
                    ->where('active', 1)
                    ->pluck('id');

                return $cache[$this->id] = $ids->unique()->values();
            }

            // All-sites ON with no branches selected => no visibility.
            return $cache[$this->id] = collect([]);
        }

        // 3) No "All Sites"; only branches selected => all sites in those branches.
        if ($this->accessibleBranches->isNotEmpty()) {
            $branchIds = $this->accessibleBranches->pluck('id')->unique()->values();

            $ids = DB::table('sites')
                ->whereIn('branch_id', $branchIds)
                ->where('active', 1) // Only active sites
                ->pluck('id');

            return $cache[$this->id] = $ids->unique()->values();
        }

        // 4) No explicit access => empty set (fail closed).
        return $cache[$this->id] = collect([]);
    }
}
