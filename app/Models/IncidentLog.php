<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_report_id', // Changed from incident_id
        'user_id',
        'action_type',
        'old_value',
        'new_value',
        'description',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the incident report that owns the log.
     */
    public function incidentReport(): BelongsTo
    {
        return $this->belongsTo(IncidentReport::class);
    }

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a status change log entry.
     */
    public static function logStatusChange(int $incidentReportId, ?int $userId, string $oldStatus, string $newStatus): self
    {
        return self::create([
            'incident_report_id' => $incidentReportId,
            'user_id' => $userId,
            'action_type' => 'status_changed',
            'old_value' => $oldStatus,
            'new_value' => $newStatus,
            'description' => "Status changed from {$oldStatus} to {$newStatus}",
        ]);
    }

    /**
     * Create an assignment log entry.
     */
    public static function logAssignment(int $incidentReportId, ?int $userId, ?int $oldAssigneeId, ?int $newAssigneeId): self
    {
        $oldAssignee = $oldAssigneeId ? User::find($oldAssigneeId)?->name : 'Unassigned';
        $newAssignee = $newAssigneeId ? User::find($newAssigneeId)?->name : 'Unassigned';

        return self::create([
            'incident_report_id' => $incidentReportId,
            'user_id' => $userId,
            'action_type' => 'assigned',
            'old_value' => (string) $oldAssigneeId,
            'new_value' => (string) $newAssigneeId,
            'description' => "Assignment changed from {$oldAssignee} to {$newAssignee}",
        ]);
    }

    /**
     * Create an archive log entry.
     */
    public static function logArchive(int $incidentReportId, ?int $userId): self
    {
        return self::create([
            'incident_report_id' => $incidentReportId,
            'user_id' => $userId,
            'action_type' => 'archived',
            'description' => 'Incident archived',
        ]);
    }

    /**
     * Create an unarchive log entry.
     */
    public static function logUnarchive(int $incidentReportId, ?int $userId): self
    {
        return self::create([
            'incident_report_id' => $incidentReportId,
            'user_id' => $userId,
            'action_type' => 'unarchived',
            'description' => 'Incident restored from archive',
        ]);
    }
}
