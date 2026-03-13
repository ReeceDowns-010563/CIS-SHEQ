<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidentComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id',
        'user_id',
        'comment',
        'is_system_comment',
    ];

    protected $casts = [
        'is_system_comment' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function incident(): BelongsTo
    {
        return $this->belongsTo(IncidentReport::class, 'incident_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
