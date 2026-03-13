<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Branch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_code',
        'branch_name',
        'TimeGateId',
        'active',
    ];

    /**
     * Get the sites for the branch.
     */
    public function sites(): HasMany
    {
        // Primary relationship using branch_id for consistency
        return $this->hasMany(Site::class, 'branch_id', 'id');
    }

    /**
     * Get the sites for the branch (code-based relationship).
     * Keep this for backward compatibility.
     */
    public function sitesByCode(): HasMany
    {
        return $this->hasMany(Site::class, 'branch_code', 'branch_code');
    }

    /**
     * Get the employees for the branch through sites.
     */
    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Employee::class,
            \App\Models\Site::class,
            'branch_id',   // Foreign key on sites table (updated)
            'site_id',     // Foreign key on employees table
            'id',          // Local key on branches table (updated)
            'id'           // Local key on sites table
        );
    }

    /**
     * Get the employees for the branch through sites (code-based).
     * Keep this for backward compatibility.
     */
    public function employeesByCode(): HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Employee::class,
            \App\Models\Site::class,
            'branch_code', // Foreign key on sites table
            'site_id',     // Foreign key on employees table
            'branch_code', // Local key on branches table
            'id'           // Local key on sites table
        );
    }

    /**
     * Scope a query to search branches by code or name.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('branch_code', 'like', "%{$search}%")
                ->orWhere('branch_name', 'like', "%{$search}%");
        });
    }

    /**
     * Scope a query to only include active branches.
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * Get the display name with code.
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->branch_code} - {$this->branch_name}";
    }
}
