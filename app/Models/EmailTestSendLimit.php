<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailTestSendLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sends_count',
        'window_start',
    ];

    protected $casts = [
        'window_start' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function canSend(int $hourlyLimit = 10): bool
    {
        $windowStart = now()->subHour();

        if (!$this->window_start || $this->window_start->lt($windowStart)) {
            return true;
        }

        return $this->sends_count < $hourlyLimit;
    }

    public function incrementSends(): void
    {
        $windowStart = now()->subHour();

        if (!$this->window_start || $this->window_start->lt($windowStart)) {
            $this->update([
                'sends_count' => 1,
                'window_start' => now(),
            ]);
        } else {
            $this->increment('sends_count');
        }
    }
}
