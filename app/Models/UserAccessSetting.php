<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAccessSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'all_branches',
        'all_sites',
    ];

    protected $casts = [
        'all_branches' => 'boolean',
        'all_sites' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
