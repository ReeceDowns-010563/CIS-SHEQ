<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class IncidentReport extends Model
{
    use HasFactory;

    protected $table = 'incident_reports';

    protected $fillable = [
        'brief_description',
        'incident_type_id',
        'incident_type_other_description', // ADDED
        'location',
        'additional_information',
        'attachments',

        // Affected Person
        'affected_person_source',
        'affected_employee_id',
        'affected_customer_id',
        'affected_person_other',

        // Reported By
        'reported_by_source',
        'reported_employee_id',
        'reported_customer_id',
        'reported_by_other',

        // Incident Details
        'treatment_type_id',
        'mechanism_id',
        'physician_details',
        'date_of_occurrence',
        'time_of_occurrence',

        // Work Details
        'branch_id',
        'site_id',
        'agency_id',

        // Medical & Incident Specifics
        'body_part_id', // Now stores JSON array of IDs
        'body_part_other',
        'injury_type_id',
        'injury_type_other',
        'what_happened',

        // Administrative
        'coordinator_id',
        'created_by',
        'updated_by',
        'status',

        // Archive fields
        'archived',
        'archived_by',
        'archived_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'body_part_id' => 'array', // Cast to array for multi-select
        'date_of_occurrence' => 'date',
        'archived' => 'boolean',
        'archived_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $incident) {
            if (empty($incident->status)) {
                $incident->status = 'pending';
            }
        });
    }

    // Relationships

    public function incidentType(): BelongsTo
    {
        return $this->belongsTo(IncidentType::class);
    }

    public function treatmentType(): BelongsTo
    {
        return $this->belongsTo(TreatmentType::class);
    }

    public function mechanism(): BelongsTo
    {
        return $this->belongsTo(Mechanism::class);
    }

    // Updated method for multiple body parts (not a relationship anymore)
    public function bodyParts()
    {
        if (empty($this->body_part_id)) {
            return collect();
        }

        return \App\Models\BodyPart::whereIn('id', $this->body_part_id)->get();
    }

    // REMOVED: The old bodyPart relationship since it conflicts with array data
    // public function bodyPart(): BelongsTo
    // {
    //     return $this->belongsTo(BodyPart::class);
    // }

    public function injuryType(): BelongsTo
    {
        return $this->belongsTo(InjuryType::class);
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function affectedEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'affected_employee_id');
    }

    public function affectedCustomer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'affected_customer_id');
    }

    public function reportedEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reported_employee_id');
    }

    public function reportedCustomer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'reported_customer_id');
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function archivedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(IncidentComment::class, 'incident_id')->orderBy('created_at', 'desc');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(IncidentLog::class, 'incident_report_id');
    }

    public function correctiveActions(): MorphMany
    {
        return $this->morphMany(CorrectiveAction::class, 'actionable');
    }

    // Accessors

    public function getCommentsCountAttribute(): int
    {
        return $this->comments()->count();
    }

    // ADDED: Helper method to get incident type display name
    public function getIncidentTypeNameAttribute(): string
    {
        if ($this->incident_type_id && $this->incidentType) {
            return $this->incidentType->name;
        }

        if ($this->incident_type_other_description) {
            return $this->incident_type_other_description;
        }

        return 'Not specified';
    }

    // Updated helper method to get body part display names (plural)
    public function getBodyPartNamesAttribute(): string
    {
        if (!empty($this->body_part_id) && is_array($this->body_part_id)) {
            $bodyParts = $this->bodyParts();
            if ($bodyParts->isNotEmpty()) {
                return $bodyParts->pluck('name')->join(', ');
            }
        }

        if ($this->body_part_other) {
            return $this->body_part_other;
        }

        return 'Not specified';
    }

    // Keep the old accessor for backwards compatibility - but now it uses the new logic
    public function getBodyPartNameAttribute(): string
    {
        return $this->getBodyPartNamesAttribute();
    }

    // Helper method to get injury type display name
    public function getInjuryTypeNameAttribute(): string
    {
        if ($this->injury_type_id && $this->injuryType) {
            return $this->injuryType->name;
        }

        if ($this->injury_type_other) {
            return $this->injury_type_other;
        }

        return 'Not specified';
    }

    // Scopes for archive functionality
    public function scopeActive($query)
    {
        return $query->where('archived', false);
    }

    public function scopeArchived($query)
    {
        return $query->where('archived', true);
    }
}
