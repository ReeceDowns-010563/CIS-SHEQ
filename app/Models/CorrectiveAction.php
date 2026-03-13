<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CorrectiveAction extends Model
{
    use HasFactory;

    protected $table = 'investigation_corrective_actions';

    protected $fillable = [
        'actionable_type',
        'actionable_id',
        'corrective_actions',
        'user_id',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Polymorphic relationship to the form (incident or complaint)
     */
    public function actionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * User who added the corrective actions
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
