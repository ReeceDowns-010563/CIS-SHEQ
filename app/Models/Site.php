<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'site_code',
        'name',
        'branch_code',
        'TimeGateId',
        'customer_id',
        'branch_id',
        'address',
        'city',
        'county',
        'postal_code',
        'description',
        'active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get the branch that owns the site.
     */
    public function branch(): BelongsTo
    {
        // Support both relationship types for backward compatibility
        // Primary relationship uses branch_id for consistency with the forms
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    /**
     * Get the branch that owns the site (code-based relationship).
     * Keep this for backward compatibility with existing data.
     */
    public function branchByCode(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_code', 'branch_code');
    }

    /**
     * Get the customer that owns the site.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the employees for the site.
     */
    public function employees(): HasMany
    {
        // Updated to use site_id as the foreign key for consistency
        return $this->hasMany(Employee::class, 'site_id', 'id');
    }

    /**
     * Get the users that have access to this site.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_site_access')->withTimestamps();
    }

    /**
     * Scope a query to only include active sites.
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * Scope a query to filter sites by branch using branch_id.
     */
    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Scope a query to filter sites by branch code.
     * Keep this for backward compatibility.
     */
    public function scopeForBranchCode($query, $branchCode)
    {
        return $query->where('branch_code', $branchCode);
    }

    /**
     * Scope a query to search sites by name or code.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('site_code', 'like', "%{$search}%");
        });
    }
}
