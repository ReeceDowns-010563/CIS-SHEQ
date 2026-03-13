<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_key',
        'subject',
        'recipients',
        'origin',
        'variables',
        'redacted_variables',
        'provider_id',
        'status',
        'error_message',
        'sent_at',
        'duration_ms',
        'user_id',
        'ip_address',
    ];

    protected $casts = [
        'recipients' => 'array',
        'variables' => 'array',
        'redacted_variables' => 'array',
        'sent_at' => 'datetime',
    ];

    protected $dates = [
        'sent_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'template_key', 'key');
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeByOrigin(Builder $query, string $origin): Builder
    {
        return $query->where('origin', $origin);
    }

    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function getRecipientsList(): array
    {
        return $this->recipients ?? [];
    }

    public function getDurationInSeconds(): ?float
    {
        return $this->duration_ms ? $this->duration_ms / 1000 : null;
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'sent' => 'status-completed',
            'failed' => 'status-closed',
            'pending' => 'status-pending',
            default => 'status-pending',
        };
    }
}
