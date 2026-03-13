<?php

namespace App\Models;

use App\Scopes\SiteOwnedScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_number',
        'timegate_id',
        'first_name',
        'last_name',
        'email',
        'number',
        'site_id',
        'site_code',
        'status',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new SiteOwnedScope);
    }

    // Relations

    public function site(): BelongsTo
    {
        // Updated to use site_id as foreign key since that's what the form is using
        return $this->belongsTo(Site::class, 'site_id', 'id');
    }

    // Accessors

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Scopes

    public function scopeSearch(Builder $query, string $search): void
    {
        $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('employee_number', 'like', "%{$search}%")
                ->orWhere('timegate_id', 'like', "%{$search}%");
        });
    }

    public function scopeForSite(Builder $query, $siteId): void
    {
        // Updated to use site_id instead of site_code
        $query->where('site_id', $siteId);
    }

    public function scopeForBranch(Builder $query, $branchId): void
    {
        // Updated to use branch_id in the site relationship
        $query->whereHas('site', function ($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        });
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', 'active');
    }
}
