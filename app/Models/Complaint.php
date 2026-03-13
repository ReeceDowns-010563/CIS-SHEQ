<?php

namespace App\Models;

use App\Scopes\SiteOwnedScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_received',
        'name',
        'pcn_number',
        'site_id',
        'nature',
        'date_acknowledged',
        'status',
        'assigned_to',
        'conclusion',
        'date_concluded',
        'ico_complaint',
        'archived',
        'archived_by',
        'archived_at',
    ];

    protected $casts = [
        'archived' => 'boolean',
        'archived_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new SiteOwnedScope);

        static::creating(function (self $complaint) {
            if (!isset($complaint->status)) {
                $complaint->status = 'open';
            }
        });
    }

    // Relations

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function archivedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ComplaintLog::class);
    }

    /**
     * Get the comments for the complaint.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(ComplaintComment::class)->orderBy('created_at', 'desc');
    }

    // Accessors

    public function getDateReceivedAttribute($value): ?string
    {
        return $value ? date('d/m/Y', strtotime($value)) : null;
    }

    public function getDateAcknowledgedAttribute($value): ?string
    {
        return $value ? date('d/m/Y', strtotime($value)) : null;
    }

    public function getDateConcludedAttribute($value): ?string
    {
        return $value ? date('d/m/Y', strtotime($value)) : null;
    }

    public function getArchivedAtAttribute($value): ?string
    {
        return $value ? date('d/m/Y', strtotime($value)) : null;
    }

    public function getDateReceivedForInputAttribute(): string
    {
        $raw = $this->getRawOriginal('date_received');
        return $raw ? date('Y-m-d', strtotime($raw)) : '';
    }

    public function getDateAcknowledgedForInputAttribute(): string
    {
        $raw = $this->getRawOriginal('date_acknowledged');
        return $raw ? date('Y-m-d', strtotime($raw)) : '';
    }

    public function getDateConcludedForInputAttribute(): string
    {
        $raw = $this->getRawOriginal('date_concluded');
        return $raw ? date('Y-m-d', strtotime($raw)) : '';
    }

    /**
     * Get the count of comments for the complaint.
     */
    public function getCommentsCountAttribute(): int
    {
        return $this->comments()->count();
    }

    // Mutators

    public function setStatusAttribute($value): void
    {
        $this->attributes['status'] = $value;

        if ($value === 'open') {
            $this->attributes['date_concluded'] = null;
        } elseif ($value === 'closed' && empty($this->attributes['date_concluded'])) {
            $this->attributes['date_concluded'] = now();
        }
    }

    // Scopes

    public function scopeActive(Builder $query): void
    {
        $query->where('archived', false);
    }

    public function scopeArchived(Builder $query): void
    {
        $query->where('archived', true);
    }

    public function scopeWithStatus(Builder $query, $status): void
    {
        if ($status) {
            $query->where('status', $status);
        }
    }

    public function scopeWithSite(Builder $query, $siteId): void
    {
        if ($siteId) {
            $query->where('site_id', $siteId);
        }
    }

    public function scopeWithAssignedTo(Builder $query, $userId): void
    {
        if ($userId) {
            $query->where('assigned_to', $userId);
        }
    }
}
