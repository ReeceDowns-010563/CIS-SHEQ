<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Guide extends Model
{
    protected $fillable = [
        'title',
        'description',
        'is_public',
        'file_path',
        // share_token is handled via methods
    ];

    protected static function booted()
    {
        static::creating(function (Guide $g) {
            if (empty($g->uuid)) {
                $g->uuid = Str::uuid();
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function generateShareToken(): void
    {
        $this->share_token = Str::random(40);
        $this->save();
    }

    public function revokeShareToken(): void
    {
        $this->share_token = null;
        $this->save();
    }

    public function getPublicUrl(): string
    {
        return route('guides.show', ['uuid' => $this->uuid]);
    }

    public function getSharedUrl(): ?string
    {
        if (! $this->share_token) {
            return null;
        }

        return route('guides.shared', ['uuid' => $this->uuid, 'token' => $this->share_token]);
    }
}
