<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintLog extends Model
{
    protected $fillable = [
        'complaint_id',
        'user_id',
        'action_type',
        'changes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'changes' => 'array',
    ];

    /**
     * Get the complaint associated with the log entry.
     */
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    /**
     * Get the user who performed the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
